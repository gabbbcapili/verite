<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['email_footer','spaf_completed','spaf_reminder','spaf_create',
                            'spaf_resend','user_reset','user_welcome', 'user_changed_role',
                            'admin_change_role_of','welcome_client','welcome_supplier'];
}
