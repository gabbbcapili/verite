<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedBy;
use App\Models\Spaf;
use App\Models\SpafAnswer;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;
    use CreatedUpdatedBy;

    protected $table = 'report';

    protected $fillable = ['title', 'audit_id', 'content', 'created_by', 'updated_by', 'google_drive_link', 'final_pdf', 'status'];

    public function audit(){
        return $this->belongsTo(Audit::class, 'audit_id');
    }

    public function reviews(){
        return $this->hasMany(ReportReview::class, 'report_id');
    }

    public function getStatusTextAttribute(){
        switch($this->status){
            case 0: return 'Pending'; break;
            case 1: return 'For Review'; break;
            case 2: return 'Approved - Waiting for Final PDF'; break;
            case 3: return 'Closed'; break;
            default: return 'Unknown';
        }
    }

    public function getStatusDisplayAttribute(){
        $statusText = $this->status_text;
        if($this->status == 0){
            return '<span class="badge rounded-pill badge-light-danger  me-1">'. $statusText .'</span>';
        }elseif($this->status == 1){
            return '<span class="badge rounded-pill badge-light-warning  me-1">'. $statusText .'</span>';
        }elseif($this->status == 2){
            return '<span class="badge rounded-pill badge-light-info  me-1">'. $statusText .'</span>';
        }elseif($this->status == 3){
            return '<span class="badge rounded-pill badge-light-success  me-1">'. $statusText .'</span>';
        }else{
            return '<span class="badge rounded-pill badge-light-danger  me-1">'. $statusText .'</span>';
        }
    }

    public function getGoogleDriveLinkDisplayAttribute(){
        return '<a target="_blank" href="'. $this->google_drive_link .'">'. Str::limit($this->google_drive_link, 20, '...') .'</a>';
    }

    public function getFinalPdfDisplayAttribute(){
        return '<a target="_blank" href="/images/finalpdf/'. $this->final_pdf .'">'. Str::limit($this->final_pdf, 20, '...') .'</a>';
    }

    public function processContent(){
        $content = $this->content;
        preg_match_all('#\{(.*?)\}#', $content, $match);
        $audit = $this->audit;
        $schedule = $audit->schedule;
        $event = $schedule->event;
        $company = $schedule->client;
        $spafs = $company->loadSpafForReport();
        foreach($match[1] as $var){
            $exploded = explode('-', $var);
            $text = null;
            if(isset($exploded[0])){
                $variable = $exploded[0];
                $prop = $exploded[1];
                if($variable == 'company'){
                    $text = $prop;
                }else if($variable == 'event'){
                    $text = $prop;
                }else if($variable == 'schedule'){
                    $text = $prop;
                }else if($variable == 'template'){
                    if(count($exploded) >= 2){
                        $ids = explode('_', $exploded[1]);
                        if(count($ids) >= 2){
                            $template_id = $ids[0];
                            $question_id = $ids[1];
                            $spafAnswer = SpafAnswer::where('question_id', $question_id)
                                ->whereHas('spaf', function($query) use($template_id, $company){
                                    $query->where('template_id', $template_id);
                                    $query->where('client_id', $company->id);
                                })->orderBy('id', 'desc')->first();
                            if($spafAnswer){
                                $spafQuestion = $spafAnswer->question;
                                if($spafQuestion){
                                    if($spafQuestion->type == 'table'){
                                        $content = str_replace('{'. $var .'}', $spafQuestion->convertToTinyMceTable($spafAnswer->value), $content);
                                    }else{
                                        $content = str_replace('{'. $var .'}', $spafAnswer->value, $content);
                                    }
                                }else{
                                    $content = str_replace('{'. $var .'}', $spafAnswer->value, $content);
                                }
                            }
                        }
                    }
                }
                if($text){
                    if(isset(${$variable})){
                        if(${$variable}){
                            if(isset(${$variable}->{$text})){
                                $formattedText = ${$variable}->{$text};
                                if($prop == 'with_completed_spaf'){
                                    $formattedText = $formattedText == 1 ? 'Yes' : '';
                                }else if ($prop == 'with_quotation'){
                                    $formattedText = $formattedText == 1 ? 'Yes' : '';
                                }
                                $content = str_replace('{'. $var .'}', $formattedText, $content);
                            }

                        }
                    }
                }
            }
        }
        $this->update(['content' => $content]);
    }
}
