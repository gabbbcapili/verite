<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\ScheduleStatus;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function email()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"], ['name'=>"Settings"], ['name'=>"Emails Settings"]
        ];
        $setting = Setting::first();
        return view('app.setting.email', compact('breadcrumbs', 'setting'));
    }

    public function schedule(){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"], ['name'=>"Settings"], ['name'=>"Schedule Settings"]
        ];
        $setting = Setting::first();
        $scheduleStatuses = ScheduleStatus::where('blockable', 0)->get();
        return view('app.setting.schedule', compact('breadcrumbs', 'setting', 'scheduleStatuses'));
    }

    public function scheduleUpdate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'schedule_cf_1' => ['required'],
            'schedule_cf_2' => ['required'],
            'schedule_cf_3' => ['required'],
            'schedule_cf_4' => ['required'],
            'schedule_cf_5' => ['required'],
            'audit_program_default_status_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            Setting::first()->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Settings updated successfully!',
                        'redirect' => route('settings.schedule'),
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function emailUpdate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email_footer' => ['required'],
            'spaf_completed' => ['required'],
            'spaf_reminder' => ['required'],
            'spaf_create' => ['required'],
            'spaf_resend' => ['required'],
            'user_reset' => ['required'],
            'user_welcome' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            Setting::first()->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Settings updated successfully!',
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }
}
