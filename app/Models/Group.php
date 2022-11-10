<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';

    protected $fillable = ['header', 'sort'];

    public function questions(){
        return $this->hasMany(Question::class, 'group_id');
    }
}
