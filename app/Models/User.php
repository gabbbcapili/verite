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

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use CreatedUpdatedBy;

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

    public function getCompanyDetailsAttribute(){
        if($this->company){
            return $this->company->company_name . ' - ' . $this->first_name . ' ' . $this->last_name;
        }else{
            return $this->first_name . ' ' . $this->last_name;
        }
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function spafSupplier(){
        return $this->hasMany(Spaf::class, 'supplier_id');
    }

    public function spafClient(){
        return $this->hasMany(Spaf::class, 'client_id');
    }

    public function generatePassworResetToken(){
        $token = \Illuminate\Support\Facades\Password::broker('users')->createToken($this);
        return $token;
    }
}
