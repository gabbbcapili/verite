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

    protected $fillable = ['start_date', 'end_date', 'type', 'title', 'country_id', 'state_id'];

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
                if($this->type == 'Holiday Country'){
                    return $this->title;
                }
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
        $titleComputed = $this->titleComputed;
        $schedule = $this->schedule;
        if($schedule){
            $resources = [];
            foreach($this->users()->whereIn('role', ['Lead Auditor', 'Second Auditor'])->where('modelable_type', 'App\Models\User')->get() as $user){
                $mainUser = $user->modelable;
                if($mainUser){
                    $resources[] = $mainUser->initials;
                }
            }
            if(count($resources)){
                $titleComputed = $titleComputed . ' (' . implode(', ', $resources) . ')';
            }
        }

        return $titleComputed;
    }

    public function getGanttTooltipAttribute(){
        $titleComputed = $this->titleComputed;
        $schedule = $this->schedule;
        if($schedule){
            $resources = [];
            foreach($this->users()->where('modelable_type', 'App\Models\User')->get() as $user){
                $mainUser = $user->modelable;
                if($mainUser){
                    $resources[] = $mainUser->initials;
                }
            }
            if(count($resources)){
                $titleComputed = $titleComputed . ' (' . implode(', ', $resources) . ')';
            }
        }

        return $titleComputed;
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

    public static function filter($events, $request){
        if(! $request->user()->can('schedule.manage')){
            $events->whereHas('users', function ($q) use($request){
                $q->where(function($q1) use($request){
                    $q1->where('modelable_id', $request->user()->id);
                    $q1->where('modelable_type', 'App\Models\User');
                });
                $q->orWhere(function($q2) use($request){
                    $q2->whereIn('modelable_id', $request->user()->companies()->pluck('company_id')->toArray());
                    $q2->where('modelable_type', 'App\Models\Company');
                });
            });
        }else{
            $company = $request->company;
            $auditor = $request->auditor;
            if(! in_array($auditor, ['null', 'all']) || ! in_array($company, ['null', 'all'])){
                if(! in_array($auditor, ['null', 'all']) && ! in_array($company, ['null', 'all'])){
                    $events->whereHas('users', function ($q) use($auditor, $company){
                        $q->where(function($q1) use($auditor){
                            $q1->where('modelable_id', $auditor);
                            $q1->where('modelable_type', 'App\Models\User');
                        });
                        $q->orWhere(function($q2) use($company){
                            $q2->where('modelable_id', $company);
                            $q2->where('modelable_type', 'App\Models\Company');
                        });
                    });
                }else{
                    if(! in_array($auditor, ['null', 'all'])){
                        if($company == "all"){ // all company and selected auditor
                            $events->whereHas('users', function ($q) use($auditor){
                                $q->where(function($q1) use($auditor){
                                    $q1->where('modelable_id', $auditor);
                                    $q1->where('modelable_type', 'App\Models\User');
                                });
                            })->orWhereHas('users', function ($q){
                                $q->where(function($q1){
                                    $q1->where('modelable_type', 'App\Models\Company');
                                });
                            });
                        }else{ // selected auditor only
                            $events->whereHas('users', function ($q) use($auditor){
                                $q->where(function($q1) use($auditor){
                                    $q1->where('modelable_id', $auditor);
                                    $q1->where('modelable_type', 'App\Models\User');
                                });
                            })->orWhereHas('users', function ($q){
                                $q->where(function($q1){
                                    $q1->where('modelable_id', 1);
                                    $q1->where('modelable_type', 'App\Models\Company');
                                });
                            });
                        }
                    }
                    if(! in_array($company, ['null', 'all'])){
                        if($auditor == "all"){ // all auditor and selected company
                            $events->whereHas('users', function ($q) use($company){
                                $q->where(function($q1) use($company){
                                    $q1->where('modelable_id', $company);
                                    $q1->where('modelable_type', 'App\Models\Company');
                                });
                                $q->orWhere(function($q2) use($company){
                                    $q2->where('modelable_id', 1);
                                    $q2->where('modelable_type', 'App\Models\Company');
                                });
                            })->orWhereHas('users', function ($q) use($company){
                                $q->where(function($q1){
                                    $q1->where('modelable_type', 'App\Models\User');
                                });
                            });
                        }else{ // selected company only
                            $events->whereHas('users', function ($q) use($company){
                                $q->where(function($q1) use($company){
                                    $q1->where('modelable_id', $company);
                                    $q1->where('modelable_type', 'App\Models\Company');
                                });
                                $q->orWhere(function($q2) use($company){
                                    $q2->where('modelable_id', 1);
                                    $q2->where('modelable_type', 'App\Models\Company');
                                });
                            });
                        }
                        
                    }
                }
            }else{
                if($auditor == "null" && $company == "null"){
                    $events->whereHas('schedule')
                    ->orWhereHas('users', function ($q) use($company){
                        $q->where(function($q1){
                            $q1->where('modelable_id', 1);
                            $q1->where('modelable_type', 'App\Models\Company');
                        });
                    });
                }else{
                    if($auditor == "null"){
                        $events->whereHas('schedule')
                        ->orWhereHas('users', function ($q) use($company){
                            $q->where(function($q1){
                                $q1->where('modelable_type', 'App\Models\Company');
                            });
                        });
                    }else if ($company == "null"){
                        $events->whereHas('schedule')
                        ->orWhereHas('users', function ($q) use($company){
                            $q->where(function($q1){
                                $q1->where('modelable_type', 'App\Models\User');
                            });
                        });
                    }
                }
            }
        }
        return $events;
    }
}
