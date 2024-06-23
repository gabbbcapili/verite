<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Support\Str;
use App\Models\User;

class ReportReview extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'report_review';

    protected $fillable = ['report_id','message','target_group', 'status', 'resolve_notes', 'file'];

    public function report(){
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function getFileDisplayAttribute(){
        return '<a target="_blank" href="'. $this->file .'">'. Str::limit($this->file, 20, '...') .'</a>';
    }

    public function getTargetGroupDisplayAttribute(){
        // $groups = config('report.target_groups');
        // return isset($groups[$this->target_group]) ? $groups[$this->target_group] : '';
        $targets = User::whereIn('id', explode(',', $this->target_group))->get()->pluck('full_name');
        if($targets->count()){
            return $targets->implode(', ');
        }
    }
    
    public function getStatusClass(){
        if($this->status == 'Pending'){
            return 'danger';
        }
        return 'success';
    }
}
