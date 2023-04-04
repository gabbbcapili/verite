<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedBy;

class ScheduleStatusLog extends Model
{
    use HasFactory;
    use CreatedUpdatedBy;

    protected $table = 'schedule_status_log';

    protected $fillable = ['schedule_id', 'schedule_status_id'];

    public function scheduleStatus(){
        return $this->belongsTo(ScheduleStatus::class, 'schedule_status_id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedByNameAttribute(){
        return $this->created_by_user ?  $this->created_by_user->fullName : null;
    }

    public function getUpdatedByNameAttribute(){
        return $this->updated_by_user ? $this->updated_by_user->fullName : null;
    }
}
