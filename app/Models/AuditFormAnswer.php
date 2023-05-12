<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class AuditFormAnswer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'audit_form_answer';

    protected $fillable = ['audit_form_header_id', 'question_id', 'value'];

    public function header(){
        return $this->belongsTo(AuditFormHeader::class, 'audit_form_header_id');
    }

    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }
}

