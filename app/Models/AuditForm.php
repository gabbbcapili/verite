<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class AuditForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'audit_form';

    protected $fillable = ['audit_id','template_id','isMultiple'];

    public function audit(){
        return $this->belongsTo(Audit::class, 'audit_id');
    }

    public function headers(){
        return $this->hasMany(AuditFormHeader::class, 'audit_form_id');
    }

    public function template(){
        return $this->belongsTo(Template::class, 'template_id');
    }

}


