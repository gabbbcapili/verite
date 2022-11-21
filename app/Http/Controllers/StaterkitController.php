<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Template;


class StaterkitController extends Controller
{
    public function home()
    {
        $breadcrumbs = [['link' => "home", 'name' => "Home"], ['name' => ""]];
        $totals = [];
        $totals['users'] = User::all()->count();
        $totals['suppliers'] = Role::find(3)->users->count();

        return view('app.dashboard.index', compact('breadcrumbs', 'totals'));
    }

    public function getBadges(){
        try {
            $data['badge_spaf'] = Template::where('type', 'spaf')->where('is_approved', false)->count();
            $data['badge_spaf_extension'] = Template::where('type', 'spaf_extension')->where('is_approved', false)->count();
            $data['badge_risk_management'] = Template::where('type', 'risk_management')->where('is_approved', false)->count();

            $data['badge_templates'] = $data['badge_spaf'] + $data['badge_spaf_extension'] + $data['badge_risk_management'];
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
