<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $table = 'template';

    protected $fillable = ['name', 'type', 'is_deleted', 'is_approved'];

    public function groups(){
        return $this->hasMany(Group::class, 'template_id');
    }

    public function questions(){
        return $this->hasManyThrough(Question::class, Group::class);
    }

    public function spaf(){
        return $this->hasOne(Spaf::class, 'user_id');
    }
}