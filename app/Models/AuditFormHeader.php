<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class AuditFormHeader extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'audit_form_header';

    protected $fillable = ['audit_form_id', 'name', 'status', 'groupCompleted'];

    public function form(){
        return $this->belongsTo(AuditForm::class, 'audit_form_id');
    }

    public function answers(){
        return $this->hasMany(AuditFormAnswer::class, 'audit_form_header_id');
    }

    public function reviews(){
        return $this->hasMany(AuditReview::class, 'audit_form_header_id');
    }

    public function template(){
        return $this->form->template;
    }

    public function getStatusDisplayAttribute(){
        if($this->status == 'open'){
            return '<span class="badge rounded-pill badge-light-danger  me-1">Open</span>';
        }elseif($this->status == 'partial'){
            return '<span class="badge rounded-pill badge-light-warning  me-1">Partial</span>';
        }elseif($this->status == 'submitted'){
            return '<span class="badge rounded-pill badge-light-info  me-1">Submitted</span>';
        }elseif($this->status == 'approved'){
            return '<span class="badge rounded-pill badge-light-success  me-1">Approved</span>';
        }else{
            return '<span class="badge rounded-pill badge-light-danger  me-1">Unknown</span>';
        }
    }
}

