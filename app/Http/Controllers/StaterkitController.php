<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Template;
use App\Models\Spaf;


class StaterkitController extends Controller
{
    public function home()
    {
        $breadcrumbs = [['link' => "home", 'name' => "Home"], ['name' => ""]];
        $totals = [];
        $totals['users'] = User::all()->count();
        $totals['suppliers'] = Role::find(3)->users->count();
        $totals['clients'] = Role::find(4)->users->count();
        $totals['spafs'] = Spaf::where('status', 'completed')->count();

        return view('app.dashboard.index', compact('breadcrumbs', 'totals'));
    }

    public function getBadges(Request $request){
        try {
            $data = [];
            $user = $request->user();
            if($request->user()->hasRole('Supplier')){
                $data['badge_assessment_forms'] = $user->spafSupplier()->whereIn('status', ['pending', 'additional'])->count();
            }elseif($request->user()->hasRole('Client')){
                $data['badge_assessment_forms'] = $user->spafClient()->whereIn('status', ['pending', 'additional'])->count();
            }else{
                if($request->user()->can('template.manage')){
                    $data['badge_spaf'] = Template::where('type', 'spaf')->where('is_approved', false)->count();
                    $data['badge_spaf_extension'] = Template::where('type', 'spaf_extension')->where('is_approved', false)->count();
                    $data['badge_risk_management'] = Template::where('type', 'risk_management')->where('is_approved', false)->count();
                    $data['badge_templates'] = $data['badge_spaf'] + $data['badge_spaf_extension'] + $data['badge_risk_management'];
                }
                if($request->user()->can('spaf.manage')){
                    $data['badge_assessment_forms_admin'] = Spaf::whereIn('status',['pending', 'answered'])->count();
                }
            }

            $output = ['success' => 1,
                        'msg' => 'Fetched successfully!',
                        'data' => $data,
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
        }
        return response()->json($output);
    }
}
