<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'schedule';

    protected $fillable = ['title', 'event_id', 'client_id', 'status', 'audit_model', 'country', 'timezone', 'city', 'due_date', 'report_submitted', 'cf_1', 'cf_2', 'cf_3', 'cf_4', 'cf_5'];

    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }
}

