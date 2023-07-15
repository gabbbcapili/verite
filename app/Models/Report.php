<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedBy;


class Report extends Model
{
    use HasFactory;
    use CreatedUpdatedBy;

    protected $table = 'report';

    protected $fillable = ['title', 'audit_id', 'content', 'created_by', 'updated_by'];

    public function audit(){
        return $this->belongsTo(Audit::class, 'audit_id');
    }
}
