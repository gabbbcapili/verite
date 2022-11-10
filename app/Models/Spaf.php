<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spaf extends Model
{
    use HasFactory;

    protected $table = 'spaf';

    protected $fillable = ['user_id', 'template_id', 'status','completed_at','approved_at', 'notes'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function template(){
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function getStatusDisplayAttribute(){
        if($this->status == 'pending'){
            return '<span class="badge rounded-pill badge-light-warning  me-1">Pending</span>';
        }elseif($this->status == 'answered'){
            return '<span class="badge rounded-pill badge-light-warning  me-1">Waiting for Admin Approval</span>';
        }elseif($this->status == 'additional'){
            return '<span class="badge rounded-pill badge-light-warning  me-1">Additional Info Needed</span>';
        }elseif($this->status == 'completed'){
            return '<span class="badge rounded-pill badge-light-success  me-1">Completed</span>';
        }else{
            return '<span class="badge rounded-pill badge-light-danger  me-1">Unknown</span>';
        }
    }

    public function answers(){
        return $this->hasMany(SpafAnswer::class, 'spaf_id');
    }
}
