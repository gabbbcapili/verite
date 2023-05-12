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

    protected $fillable = ['audit_form_id', 'name'];

    public function form(){
        return $this->belongsTo(AuditForm::class, 'audit_form_id');
    }

    public function answers(){
        return $this->hasMany(AuditFormAnswer::class, 'audit_form_header_id');
    }

    public function template(){
        return $this->form->template;
    }
}

