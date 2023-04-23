<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class AuditProgram extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'audit_program';

    protected $fillable = ['schedule_id', 'start_date', 'plot_date', 'frequency', 'length', 'created_by', 'updated_by'];

    public function schedule(){
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function auditProgramDates(){
        return $this->hasMany(AuditProgramDate::class, 'audit_program_id', 'id');
    }
}
