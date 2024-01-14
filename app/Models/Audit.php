<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class Audit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'audit';

    protected $fillable = ['schedule_id', 'status', 'notes', 'approved_at'];

    public function schedule(){
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function forms(){
        return $this->hasMany(AuditForm::class, 'audit_id');
    }

    public function reports(){
        return $this->hasMany(Report::class, 'audit_id');
    }

    public function standardsIdsUsed(){
        $standardsIds = [];
        foreach($this->forms as $form){
            foreach($form->template->groups()->orderBy('sort')->get() as $group){
                foreach($group->questions()->orderBy('sort')->get() as $q){
                    foreach(explode(',', $q->standards) as $standard){
                        $standardsIds[$standard] = $standard;
                    }
                }
            }
        }
        return array_filter($standardsIds, fn($value) => !is_null($value) && $value !== '');
    }

    public function flagsUsed(){
        $flags = [];
        foreach($this->forms as $form){
            foreach($form->template->groups()->orderBy('sort')->get() as $group){
                foreach($group->questions()->orderBy('sort')->get() as $q){
                    foreach(explode(',', $q->flags) as $flag){
                        $flags[$flag] = $flag;
                    }
                }
            }
        }
        return array_filter($flags, fn($value) => !is_null($value) && $value !== '');
    }

    public function flagsFormsUsed(){
        $flags = [];
        foreach($this->forms as $form){
            foreach($form->template->groups()->orderBy('sort')->get() as $group){
                foreach($group->questions()->orderBy('sort')->get() as $q){
                    if($q->flags){
                        $flags[$form->id] = $form->id;
                    }
                }
            }
        }
        return array_filter($flags, fn($value) => !is_null($value) && $value !== '');
    }

    public function getStatusDisplayAttribute(){
        if($this->status == 'pending'){
            return '<span class="badge rounded-pill badge-light-danger  me-1">Pending</span>';
        }elseif($this->status == 'answered'){
            return '<span class="badge rounded-pill badge-light-warning  me-1">Waiting for Admin Approval</span>';
        }elseif($this->status == 'additional'){
            return '<span class="badge rounded-pill badge-light-info  me-1">Additional Info Needed</span>';
        }elseif($this->status == 'completed'){
            return '<span class="badge rounded-pill badge-light-success  me-1">Completed</span>';
        }else{
            return '<span class="badge rounded-pill badge-light-danger  me-1">Unknown</span>';
        }
    }
}

