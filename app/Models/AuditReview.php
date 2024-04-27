<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class AuditReview extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'audit_review';

    protected $fillable = ['audit_form_header_id','message','group_id', 'status'];

    public function header(){
        return $this->belongsTo(AuditFormHeader::class, 'audit_form_header_id');
    }
    
    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function getStatusClass(){
        if($this->status == 'Pending'){
            return 'danger';
        }
        return 'success';
    }
}
