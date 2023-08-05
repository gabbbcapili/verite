<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';

    protected $fillable = ['header', 'sort', 'displayed_on_schedule', 'editable'];

    public function questions(){
        return $this->hasMany(Question::class, 'group_id');
    }

    public function template(){
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function convertToTinymceTable($answers){
        $html = '<table style="border-collapse: collapse; width: 99.9989%;" border="1"><tbody>';

        foreach($this->questions()->orderBy('sort')->get() as $q){
            $html .= '<tr>';

            $answer = $answers->where('question_id', $q->id)->first();
            $html .= '</tr>';
        }
    }
}
