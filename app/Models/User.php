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

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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

    public static function roleList(){
        return [
                'Main' => ['template.manage'],
                'Suppliers' => ['supplier.manage', 'supplier.approve'],
                'Users' => ['user.manage', 'role.manage'],
        ];
    }

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function spaf(){
        return $this->hasOne(Spaf::class, 'user_id');
    }

    public function generatePassworResetToken(){
        // This is set in the .env file
        // $key = config('app.key');

        // // Illuminate\Support\Str;
        // if (Str::startsWith($key, 'base64:')) {
        //     $key = base64_decode(substr($key, 7));
        // }
        // $token = hash_hmac('sha256', Str::random(40), $key);
        // $token = Str::random(60);
        // DB::table('password_resets')->where('email',$this->email)->delete();
        // DB::table('password_resets')->insert([
        //         'email' => $this->email,
        //         'token' => $token,
        //         'created_at' => Carbon::now()
        // ]);
        $token = \Illuminate\Support\Facades\Password::broker('users')->createToken($this);
        return $token;
    }
}
