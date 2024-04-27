<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\EventUserObserver;

class EventUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'event_user';

    protected $fillable = ['role', 'event_id', 'modelable_id', 'modelable_type', 'blockable', 'start_date', 'end_date', 'status', 'notes'];

    protected $appends = ['status_formatted'];

    protected static function boot()
    {
        parent::boot();

        EventUser::observe(EventUserObserver::class);
    }

    public function event(){
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function modelable(){
        return $this->morphTo();
    }

    public function getStatusFormattedAttribute(){
        switch($this->status){
            case 0: return 'Pending'; break;
            case 1: return 'Accepted'; break;
            case 2: return 'Rejected'; break;
        }
    }

    public static function getStartEndDate($start_end){
        $eventUserStartEnd = explode('to', $start_end);
        $eventUserStart = $eventUserStartEnd[0];
        $eventUserEnd = array_key_exists(1, $eventUserStartEnd) ? $eventUserStartEnd[1] : $eventUserStartEnd[0];
        return ['eventUserStart' => $eventUserStart, 'eventUserEnd' => $eventUserEnd];
    }
}
