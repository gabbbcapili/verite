<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpafAnswer extends Model
{
    use HasFactory;

    protected $table = 'spaf_answer';

    protected $fillable = ['spaf_id', 'question_id', 'value'];

    public function spaf(){
        return $this->belongsTo(Spaf::class, 'spaf_id');
    }

    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }

}
