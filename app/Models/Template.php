<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedBy;

class Template extends Model
{
    use HasFactory;
    use CreatedUpdatedBy;

    protected $table = 'template';

    protected $fillable = ['name', 'type', 'is_deleted', 'is_approved', 'status'];

    public static $typeList = ['spaf', 'spaf_extension', 'risk_management'];

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

    public function groups(){
        return $this->hasMany(Group::class, 'template_id');
    }

    public function questions(){
        return $this->hasManyThrough(Question::class, Group::class);
    }

    public function spaf(){
        return $this->hasOne(Spaf::class, 'user_id');
    }

    public function getTypeDisplayAttribute(){
        return strtoupper(str_replace('_', ' ', $this->type));
    }

    public function getStatusTextAttribute(){
        return $this->status ? 'Inactive' : 'Active';
    }

}
