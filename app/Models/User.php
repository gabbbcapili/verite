<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\CreatedUpdatedBy;
use Spatie\Permission\Models\Permission;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\Traits\HasCountry;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use CreatedUpdatedBy;
    use HasCountry;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'company_id',
        'status',
        'notes',
        'skills',
        'client_preference',
        'country_id',
        'state_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'displayName',
    ];

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

    public static function roleList(){
        return [
                'Main' => ['template.manage'],
                'Suppliers' => ['supplier.manage', 'spaf.approve'],
                'Users' => ['user.manage', 'role.manage'],
        ];
    }

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getInitialsAttribute(){
        $str= $this->first_name . ' ' . $this->last_name;
        preg_match_all('/(?<=\b)\w/iu',$str,$matches);
        return mb_strtoupper(implode('',$matches[0]));
        // return substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1);
    }

    public function getDisplayNameAttribute(){
        $skills = '';
        $client_preference = '';
        if($this->skills){
            $skills = Proficiency::whereIn('id', explode(',', $this->skills))->pluck('name');
            if($skills->count() > 0){
                $skills = '('. implode(', ', $skills->toArray()) . ')';
            }
        }
        if($this->client_preference){
            $companies = Company::whereIn('id', explode(',', $this->client_preference))->pluck('company_name');
            if($companies->count() > 0){
                $client_preference = '['. implode(', ', $companies->toArray()) . ']';
            }
        }

        return $this->fullName . ' ' . $skills . ' ' . $client_preference;
    }

    public function getDisplaySkillsAttribute(){
        $skills = '';
        $client_preference = '';
        if($this->skills){
            $skills = Proficiency::whereIn('id', explode(',', $this->skills))->pluck('name');
            if($skills->count() > 0){
                $skills = '('. implode(', ', $skills->toArray()) . ')';
            }
        }
        if($this->client_preference){
            $companies = Company::whereIn('id', explode(',', $this->client_preference))->pluck('company_name');
            if($companies->count() > 0){
                $client_preference = '['. implode(', ', $companies->toArray()) . ']';
            }
        }

        return $skills . ' ' . $client_preference;
    }

    public function getCompanyDetailsAttribute(){
        if($this->company){
            return $this->company->company_name . ' - ' . $this->first_name . ' ' . $this->last_name;
        }else{
            return $this->first_name . ' ' . $this->last_name;
        }
    }

    public function events(){
        return $this->morphMany(EventUser::class, 'modelable');
    }

    public function companies(){
        return $this->belongsToMany(Company::class, 'user_company', 'user_id', 'company_id');
        // return $this->belongsTo(Company::class, 'company_id');
    }

    public function spafSupplier(){
        return $this->hasMany(Spaf::class, 'supplier_id');
    }

    public function spafClient(){
        return $this->hasMany(Spaf::class, 'client_id');
    }

    public function canSetToInactive(){
        $role = $this->roles()->first()->name;
        if($role == 'Supplier'){
            if($this->spafSupplier->where('status', '!=' , 'completed')->count() > 0){
                return false;
            }
        }else if($role == 'Client'){
            if($this->spafClient->where('status', '!=' , 'completed')->count() > 0){
                return false;
            }
        }else{
            return true;
        }
        return true;
    }

    public function generatePassworResetToken(){
        $token = \Illuminate\Support\Facades\Password::broker('users')->createToken($this);
        return $token;
    }

    public static function auditors(){
        $roleswithPermission = Permission::find(18)->getRoleNames(); //Selectable as Auditor
        return User::where('status', 1)->role($roleswithPermission)->orderBy('first_name', 'asc')->get();
    }

    public static function getAvailableAuditor($date){
        $date = explode(' to ', $date);
        $from = $date[0];
        $to = array_key_exists(1, $date) ? $date[1] : $date[0];
        $users = User::auditors();
        foreach($users as $key => $user){
            $hasEvents = $user->events()->whereHas('event', function ($q) use($from,$to){
                $q->where('start_date', '>=', $from);
                $q->where('end_date', '<=', $to);
                $q->where('blockable', true);
            })->count();
            if($hasEvents > 0){
                $users->forget($key);
            }

            $hasEvents = Event::where('start_date', '>=', $from)->where('end_date', '<=', $to)
            ->whereHas('users', function ($q){
                $q->where('modelable_id', 1);
                $q->where('modelable_type', 'App\Models\Company');
            })->count();
            if($hasEvents > 0){
                $users->forget($key);
            }
        }
        return $users;
    }

    public function isAvailableOn($date){
        $date = explode(' to ', $date);
        $from = $date[0];
        $to = array_key_exists(1, $date) ? $date[1] : $date[0];
        $event = $this->events()->whereHas('event', function ($q) use($from,$to){
            $q->where('start_date', '>=', $from);
            $q->where('end_date', '<=', $to);
        })->first();
        if($event){
            return $event;
        }
        $hasEvents = Event::where('start_date', '>=', $from)->where('end_date', '<=', $to)
            ->whereHas('users', function ($q){
                $q->where('modelable_id', 1);
                $q->where('modelable_type', 'App\Models\Company');
            })->first();
        if($hasEvents){
            return $hasEvents;
        }
        return false;
    }

    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        Mail::to($this)->send(new ResetPassword($this, $token));
        // $this->notify(new ResetPasswordNotification($token, $this));
    }
}
