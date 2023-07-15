<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedBy;

class Company extends Model
{
    use HasFactory;
    use CreatedUpdatedBy;

    protected $table = 'company';

    protected $fillable = ['company_name','address','contact_number','website', 'type', 'logo', 'acronym'];

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

    public function users(){
        return $this->hasMany(User::class);
    }

    public function suppliers(){
        return $this->belongsToMany(Company::class, 'client_suppliers', 'client_id', 'supplier_id');
    }

    public function clients(){
        return $this->belongsToMany(Company::class, 'client_suppliers', 'supplier_id', 'client_id');
    }

    public function getAvailableSuppliers($date){
        $date = explode(' to ', $date);
        $from = $date[0];
        $to = array_key_exists(1, $date) ? $date[1] : $date[0];
        $companies = $this->suppliers;
        foreach($companies as $key => $company){
            $hasEvents = $company->events()->whereHas('event', function ($q) use($from,$to){
                $q->where('start_date', '>=', $from);
                $q->where('end_date', '<=', $to);
                $q->where('blockable', true);
            })->count();
            if($hasEvents > 0){
                $companies->forget($key);
            }
        }
        return $companies;
    }
    public static function getAvailableClient($date){
        $date = explode(' to ', $date);
        $from = $date[0];
        $to = array_key_exists(1, $date) ? $date[1] : $date[0];
        $companies = Company::where('type', 'client')->get();
        foreach($companies as $key => $company){
            $hasEvents = $company->events()->whereHas('event', function ($q) use($from,$to){
                $q->where('start_date', '>=', $from);
                $q->where('end_date', '<=', $to);
                $q->where('blockable', true);
            })->count();
            if($hasEvents > 0){
                $companies->forget($key);
            }
        }
        return $companies;
    }

    public function getCompanyDetailsAttribute(){
        return $this->company_name;
    }

    public function getDisplayNameAttribute(){
        return $this->company_name;
    }

    public function getFullNameAttribute(){
        return $this->company_name;
    }

    public function getProfilePhotoUrlAttribute(){
        if($this->logo){
            return asset('images/company/logos/' . $this->logo);
        }else{
            return 'https://ui-avatars.com/api/?name=&color=7F9CF5&background=EBF4FF';
        }
    }

    public function getCompanyDisplayAttribute(){
         return '<div class="d-flex justify-content-left align-items-center">
              <div class="avatar bg-light-red me-1"><img src="'. $this->ProfilePhotoUrl .'" alt="Avatar" width="26" height="26"></div><div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">'. $this->company_name . '</span></div></div>';
    }

    public function events(){
        return $this->morphMany(EventUser::class, 'modelable');
    }

    public function schedules(){
        return $this->hasMany(Schedule::class, 'client_id');
    }

    public function isAvailableOn($date){
        $date = explode(' to ', $date);
        $from = $date[0];
        $to = array_key_exists(1, $date) ? $date[1] : $date[0];
        $event = $this->events()->whereHas('event', function ($q) use($from,$to){
            $q->where('start_date', '>=', $from);
            $q->where('end_date', '<=', $to);
            $q->where('blockable', true);
        })->first();
        if($event){
            return $event;
        }
        return false;
    }

    public function loadSpafForSchedule(){
        $type = $this->type == 'client' ? 'client_id' : 'supplier_id';
        $spafs = Spaf::where('status', 'completed')
        ->whereHas($this->type, function ($q){
            $q->where('company_id', $this->id);
        })
        ->whereHas('template', function ($q1){
            $q1->whereHas('groups', function ($q2){
                $q2->where('displayed_on_schedule', true);
            });
        })->get();
        return $spafs;
    }

    public function loadSpafForReport(){
        $type = $this->type == 'client' ? 'client_id' : 'supplier_id';
        $spafs = Spaf::where('status', 'completed')
        ->whereHas($this->type, function ($q){
            $q->where('company_id', $this->id);
        })->get();
        return $spafs;
    }
}
