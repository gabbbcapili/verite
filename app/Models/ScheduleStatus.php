<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class ScheduleStatus extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'schedule_status';

    protected $fillable = ['name', 'color', 'created_by', 'updated_by', 'blockable', 'next_stop'];

    public static $colors = ['primary', 'secondary', 'success', 'warning', 'info'];

    public function getNameDisplayAttribute(){
        return '<span class="text-'. $this->color .'">'. $this->name .'</span>';
        return $this->name;
    }

    public function schedules(){
        return $this->hasMany(Schedule::class, 'status', 'name');
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
