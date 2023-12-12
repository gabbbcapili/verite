<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Support\Str;

class Standard extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'standard';

    protected $fillable = ['name', 'created_by', 'updated_by'];

    public function getNameDisplayAttribute(){
        // return '<span style="color:'. $this->color .'">'. $this->name .'</span>';
        return $this->name;
    }

    public function getNameTruncatedAttribute(){
        return Str::of($this->name)->limit(40);
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
