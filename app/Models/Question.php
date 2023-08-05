<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'question';

    protected $fillable = ['group_id', 'text', 'type' ,'next_line' ,'sort', 'for_checkbox', 'required'];

    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }

    public static function getValidation($model){
        $validation = [];
        foreach($model->template->questions  as $q){
            if(in_array($q->type, ['checkbox', 'email', 'number', 'file', 'file_multiple', 'table'])){
                if($q->type == 'checkbox'){
                    $validation['checkbox.'. $q->id] = $q->required ? 'required' : '';
                }
                if($q->type == 'email'){
                $validation['question.'. $q->id] = $q->required ? ['required', 'email'] : ['nullable', 'email'];
                }
                if($q->type == 'number'){
                    $validation['question.'. $q->id] = $q->required ? ['required', 'numeric'] : ['nullable', 'numeric'];
                }
                if($q->type == 'file'){
                    $answer = $model->answers()->where('question_id', $q->id)->first();
                    if(! $answer){
                        $validation['file.'. $q->id] = $q->required ? ['required'] : ['nullable'];
                    }
                }
                if($q->type == 'file_multiple'){
                    $answer = $model->answers()->where('question_id', $q->id)->first();
                    if(! $answer){
                        $validation['file_multiple.'. $q->id] = $q->required ? ['required'] : ['nullable'];
                    }
                }
                if($q->type == 'table'){
                    $validation['table.'. $q->id] = $q->required ? ['required'] : ['nullable'];
                }
            }else{
                $validation['question.'. $q->id] = $q->required ? 'required' : '';
            }
        }
        return $validation;
    }

    public static function getValidationMessages(){
        return [
            'question.*.required' => 'This field is required.',
            'file.*.required' => 'This field is required.',
            'table.*.required' => 'This field is required.',
            'file_multiple.*.required' => 'This field is required.',
            'question.*.email' => 'This field must be a valid email address.',
            'question.*.numeric' => 'This field must be a number.',
            'checkbox.*.required' => 'This field is required.'
        ];
    }

    public static function processAnswers($request, $model, $uploadTo){
        if($request->has('question')){
                foreach($request->question as $name => $q){
                    $model->answers()->updateOrCreate(['question_id' => $name,],['value' => $q,
                    ]);
                }
            }
            if($request->has('checkbox')){
                foreach($request->checkbox as $name => $q){
                    $model->answers()->updateOrCreate(['question_id' => $name,],[
                            'value' => implode(',', $q),
                        ]);
                }
            }

            if($request->has('table')){
                $updated = [];
                foreach($request->table as $question_id => $answers){
                    $answer = $model->answers()->updateOrCreate(['question_id' => $question_id,],[
                            'value' => json_encode($answers),
                        ]);
                    $updated[] =$answer->id;
                }
                if(class_basename($model) == 'AuditFormHeader'){
                    $deleteTable = $model->answers()->whereIn('question_id', $model->form->template->questions->where('type', 'table')->pluck('id'))->whereNotIn('id', $updated)->delete();
                }else{
                    $deleteTable = $model->answers()->whereIn('question_id', $model->template->questions->where('type', 'table')->pluck('id'))->whereNotIn('id', $updated)->delete();
                }

            }

            if($request->has('file')){
                foreach($request->file as $name => $q){
                      $file = $q;
                      $new_name = 'file_'  . sha1(time()) . '.' . $file->getClientOriginalExtension();
                      $file->move(public_path($uploadTo) , $new_name);
                        $model->answers()->updateOrCreate(['question_id' => $name,],[
                            'value' => $new_name,
                        ]);
                }
            }
            if($request->has('file_multiple')){
                foreach($request->file_multiple as $name => $q){
                    $files = [];
                    $iteration = 0;
                    foreach($q as $file){
                        $new_name = 'file'.$iteration.'_'  . sha1(time()) . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path($uploadTo) , $new_name);
                        $files[] = $new_name;
                        $iteration += 1;
                    }
                    $model->answers()->updateOrCreate(['question_id' => $name,],[
                            'value' => implode(',', $files),
                        ]);
                }
            }
    }

    public function convertToTinyMceTable($answer){
        $q = $this;
        $html = '<table style="border-collapse: collapse; width: 99.9989%;" border="1"><tbody>';
        $html .= '<tr>';
        foreach(explode('|', $q->for_checkbox) as $column){
            $html .= '<th>'. $column .'</th>';
        }
        $html .= '</tr>';
        $totals = [];
        if($answer){
            foreach(json_decode($answer) as $index => $row){
                // $totals[$index] = 0;
                $html .= '<tr>';
                    foreach($row as $indexx => $data){
                        $totals[$indexx] = 0;
                        if(ctype_digit($data)){
                            $totals[$indexx] += (int) $data;
                        }
                        $html .= '<td>'. $data .'</td>';
                    }
                $html .= '</tr>';
                }
            $html .= '<tr>';
            foreach($totals as $total){
                if($total == 0){
                    $html .= '<td></td>';
                }else{
                    $html .= '<td>' . $total . '</td>';
                }

            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
       return $html;
    }



}
