<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class AuditForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'audit_form';

    protected $fillable = ['audit_id','template_id','isMultiple'];

    public function audit(){
        return $this->belongsTo(Audit::class, 'audit_id');
    }

    public function headers(){
        return $this->hasMany(AuditFormHeader::class, 'audit_form_id');
    }

    public function template(){
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function getTypeDisplay(){
        $text = $this->isMultiple ? 'Multiple' : 'One Time';
        $class = $this->isMultiple ? 'danger' : 'info';
        return '<span class="text-'. $class .'">'. $text .'</span>';
    }

    public function summarizeAnswers($standard = null, $flag = null){
        $headers = $this->headers;
        $summarized = [];
        foreach($this->template->groups()->orderBy('sort')->get() as $group){
            $summarized[$group->id] = ['name' => $group->header, 'questions' => []];
            foreach($group->questions()->orderBy('sort')->get() as $q){
                if(! in_array($q->type, ['file', 'file_multiple', 'table'])){
                    if($standard != null && ! in_array($standard, explode(',', $q->standards))){
                        continue;
                    }
                    if($flag != null && ! in_array($flag, explode(',', $q->flags))){
                        continue;
                    }
                    $summarized[$group->id]['questions'][$q->id] = ['text' => $q->text, 'audit_form' => $this->id, 'answers' => [],];
                    foreach($headers as $header){
                        $answers = $header->answers;
                        $qAnswer = $answers->where('question_id', $q->id)->first();
                        if($qAnswer && $qAnswer->value){
                            $found = false;
                            foreach($summarized[$group->id]['questions'][$q->id]['answers'] as $key => $val){
                                if($val['value'] == $qAnswer->value){
                                    $found = true;
                                    $summarized[$group->id]['questions'][$q->id]['answers'][$key]['times'] += 1;
                                }
                            }
                            if(! $found){
                                $summarized[$group->id]['questions'][$q->id]['answers'][$qAnswer->id] = ['value' => $qAnswer->value, 'times' => 1,];
                            }
                        }
                    }
                }
            }
        }
        $html = '';
        foreach($summarized as $key => $group){
            $html .= '<div class="row mb-5 " id="for-insertion-'. $key .'">';
            $html .= '<table style="border-collapse: collapse; width: 99.9989%;" border="1"><tbody>';
            $html .= '<tr><th style="text-align:left;" colspan="2" class="forInsertion forInsertionWithTarget" data-target="for-insertion-'. $key .'">'. $group['name'] .'</th></tr>';
            foreach($group['questions'] as $keyy => $questions){
                $html .= '<tr>';
                $html .= '<th align="left" width="50%">'. $questions['text'] .'</th>';
                $html .= '<td align="left">';
                foreach($questions['answers'] as $answer){
                    $html .= '<a target="_blank" href="'. route('report.showQuestionSummary', ['auditForm' => $questions['audit_form'], 'question' => $keyy, 'search' => $answer['value']]) .'">';
                    $html .= $answer['value'] . ' - <b>' . $answer['times'] . '</b><br>';
                    $html .= '</a>';
                }
                $html .= '</td>';
                $html .= '</tr>';
            }
            $html .= '<tr><th align="left">Total Submission</th><td align="left"><b>'. $headers->count() .'</b></td></tr>';
            $html .= '</tbody></table></div>';
        }
        return $html;
    }

}

