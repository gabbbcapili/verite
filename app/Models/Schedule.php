<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Traits\CreatedUpdatedBy;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'schedule';

    protected $fillable = ['title', 'event_id', 'client_id', 'status', 'audit_model', 'audit_model_type', 'country', 'timezone', 'city', 'due_date', 'report_submitted', 'cf_1', 'cf_2', 'cf_3', 'cf_4', 'cf_5', 'with_completed_spaf', 'with_quotation','status_color'];

    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public static function computeTitle($client, $supplier, $country_name, $start_date){
        $title = $client->modelable->acronym . '-';

        if($supplier){
            $title .= $supplier->modelable->acronym . '-';
        }
        $title .= $country_name . '-' . Carbon::parse($start_date)->format('mdy');

        return $title;
    }

    public function scheduleStatusLogs(){
        return $this->hasMany(ScheduleStatusLog::class, 'schedule_id');
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

