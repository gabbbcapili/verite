<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'event_user';

    protected $fillable = ['role', 'event_id', 'modelable_id', 'modelable_type', 'blockable'];

    public function event(){
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function modelable(){
        return $this->morphTo();
    }
}
