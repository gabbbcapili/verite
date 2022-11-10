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



}
