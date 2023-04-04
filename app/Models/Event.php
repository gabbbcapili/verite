<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'event';

    protected $fillable = ['start_date', 'end_date', 'type'];

    public function users(){
        return $this->hasMany(EventUser::class, 'event_id');
    }

    public function schedule(){
        return $this->hasOne(Schedule::class, 'event_id');
    }

    public function getTitleComputedAttribute(){
        $schedule = $this->schedule;
        if($schedule){
            return $schedule->title;
        }else{
            if($this->type != 'Audit Schedule'){
                $owner = $this->users->first();
                if($owner){
                    if($owner->modelable){
                        return $owner->modelable->fullName . ' - ' . $this->type;
                    }
                }
            }
            return $this->type;
        }
    }

    public function getPersonDaysAttribute(){
        $users = $this->users()->where('modelable_type', 'App\Models\User')->count();
        $days = Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date));
        $days = $days == 0 ? 1 : $days;
        return $users * $days;
    }

    public function getGanttTitleAttribute(){
        return $this->titleComputed;
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
