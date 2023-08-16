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

    public function summarizeAnswers(){
        $headers = $this->headers;
        $summarized = [];
        foreach($this->template->groups()->orderBy('sort')->get() as $group){
            $summarized[$group->id] = ['name' => $group->header, 'questions' => []];
            foreach($group->questions()->orderBy('sort')->get() as $q){
                if(! in_array($q->type, ['file', 'file_multiple', 'table'])){
                    $summarized[$group->id]['questions'][$q->id] = ['text' => $q->text, 'answers' => []];
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
                                $summarized[$group->id]['questions'][$q->id]['answers'][$qAnswer->id] = ['value' => $qAnswer->value, 'times' => 1];
                            }
                        }
                    }
                }
            }
        }
        $html = '';
        foreach($summarized as $group){
            $html .= '<div class="row mb-5 forInsertion">';
            $html .= '<table style="border-collapse: collapse; width: 99.9989%;" border="1"><tbody>';
            $html .= '<tr><th colspan="2">'. $group['name'] .'</th></tr>';
            foreach($group['questions'] as $questions){
                $html .= '<tr>';
                $html .= '<th align="left" width="50%">'. $questions['text'] .'</th>';
                $html .= '<td align="left">';
                foreach($questions['answers'] as $answer){
                    $html.= $answer['value'] . ' - ' . $answer['times'] . '<br>';
                }
                $html .= '</td>';
                $html .= '</tr>';
            }
            $html .= '<tr><th align="left">Total Submission</th><td align="left">'. $headers->count() .'</td></tr>';
            $html .= '</tbody></table></div>';
        }
        return $html;
    }

}

