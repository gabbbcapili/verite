<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Utilities;

class Spaf extends Model
{
    use HasFactory;

    protected $table = 'spaf';

    protected $fillable = ['client_id', 'supplier_id', 'template_id', 'status','completed_at','approved_at', 'notes'];


    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }

    public function supplier(){
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function template(){
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function getStatusDisplayAttribute(){
        if($this->status == 'pending'){
            return '<span class="badge rounded-pill badge-light-danger  me-1">Pending</span>';
        }elseif($this->status == 'answered'){
            return '<span class="badge rounded-pill badge-light-warning  me-1">Waiting for Admin Approval</span>';
        }elseif($this->status == 'additional'){
            return '<span class="badge rounded-pill badge-light-info  me-1">Additional Info Needed</span>';
        }elseif($this->status == 'completed'){
            return '<span class="badge rounded-pill badge-light-success  me-1">Completed</span>';
        }else{
            return '<span class="badge rounded-pill badge-light-danger  me-1">Unknown</span>';
        }
    }

    public function answers(){
        return $this->hasMany(SpafAnswer::class, 'spaf_id');
    }

    public static function datatables($spaf, $request){
        return Datatables::eloquent($spaf)
            ->addColumn('action', function(Spaf $spaf, Request $request) {
                            $html = '';
                            if($request->user()->hasRole('Supplier') || $request->user()->hasRole('Client')){
                                $html .= Utilities::actionButtons([['route' => route('spaf.show', $spaf->id), 'name' => 'Show', 'type' => 'href']]);
                            }else{
                                if(in_array($spaf->status, ['pending'])){
                                    $html .= '<a href="#" data-bs-toggle="tooltip" data-placement="top" title="Send Reminder Email" data-action="'. route('spaf.sendReminder', $spaf) .'" class="me-75 confirm" data-title="Are you sure to send reminder email?" ><i data-feather="send"></i></a>';
                                }
                                if(in_array($spaf->status, ['answered', 'completed'])){
                                    $html .= Utilities::actionButtons([['route' => route('spaf.show', $spaf->id), 'name' => 'Show', 'type' => 'href']]);
                                }
                            }
                            return $html;
                        })
            ->addColumn('clientName', function(Spaf $spaf) {
                            return $spaf->client->fullName;
                        })
            ->addColumn('supplierName', function(Spaf $spaf) {
                            if($spaf->supplier){
                                return $spaf->supplier->fullName;
                            }
                        })
            ->addColumn('templateName', function(Spaf $spaf) {
                            return $spaf->template->name;
                        })
            ->addColumn('type', function(Spaf $spaf) {
                            return $spaf->template->typeDisplay;
                        })
            ->addColumn('status', function(Spaf $spaf) {
                            return $spaf->statusDisplay;
                        })
            ->editColumn('created_at', function (Spaf $spaf) {
                return $spaf->created_at->format('M d, Y');
            })
            ->editColumn('updated_at', function (Spaf $spaf) {
                return $spaf->updated_at->diffForHumans();
            })
            ->rawColumns(['action', 'status', 'clients'])
            ->make(true);
    }
}
