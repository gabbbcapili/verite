<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Auth;
use App\Models\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;


class SpafTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type)
    {
        if(! in_array($type, Template::$typeList)){
            abort(404);
        }
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('template.spaf.index', ['type' => $type]), 'name'=> strtoupper(str_replace('_', ' ', $type)) ], ['name'=>"list of Templates"]
        ];
        if (request()->ajax()) {
            $template = Template::where('is_deleted', 0)->where('type', $type);
            return Datatables::eloquent($template)
            ->addColumn('action', function(Template $template) {
                            $html = '';
                            if(request()->user()->can('template.approve') && $template->is_approved == false){
                             $html .= Utilities::actionButtons([['route' => route('template.spaf.approve', $template->id), 'name' => 'Approve', 'type' => 'approve']]);
                            }
                            if(request()->user()->can('template.manage')){
                                if($template->is_approved == false){
                                    $html .= Utilities::actionButtons([
                                        ['route' => route('template.spaf.edit', $template->id), 'name' => 'Edit', 'type' => 'href'],
                                        ['route' => route('template.spaf.delete', $template->id), 'name' => 'Delete'],
                                        ['route' => route('template.spaf.clone', $template->id), 'type' => 'confirmWithNotes', 'name' => 'confirmWithNotes', 'title' => 'Clone', 'text' => 'Template Name:', 'confirmButtonText' => 'Clone']
                                    ]);
                                }else{
                                    $html .= Utilities::actionButtons([
                                        ['route' => route('template.spaf.show', ['template' => $template->id]), 'name' => 'Show'],
                                        ['route' => route('template.spaf.changeStatus', $template->id), 'name' => 'archive', 'type' => 'confirm', 'title' => 'Are you sure to change status to ' . $template->statusText . '?', 'text' => 'Change Status'],
                                        ['route' => route('template.spaf.clone', $template->id), 'type' => 'confirmWithNotes', 'name' => 'confirmWithNotes', 'title' => 'Clone', 'text' => 'Template Name:', 'confirmButtonText' => 'Clone']
                                    ]);
                                }
                            }else{
                                $html .= Utilities::actionButtons([
                                     ['route' => route('template.spaf.show', ['template' => $template->id]), 'name' => 'Show'],
                                ]);
                            }
                            return $html;
                        })
            ->editColumn('created_at', function (Template $template) {
                return $template->created_at->format('M d, Y') . ' | ' . $template->createdByName;
            })
            ->editColumn('updated_at', function (Template $template) {
                return $template->updated_at->diffForHumans() . ' | ' . $template->updatedByName;
            })
            ->addColumn('statusText', function (Template $template) {
                return $template->status ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>';
            })
            ->addColumn('is_approved', function (Template $template) {
                if($template->is_approved){
                 return '<span class="badge rounded-pill badge-light-success  me-1">Approved</span>';
                }else{
                    return '<span class="badge rounded-pill badge-light-danger  me-1">Waiting for Approval</span>';
                }
            })
            ->rawColumns(['action', 'is_approved', 'statusText'])
            ->make(true);
        }
        return view('app.template.spaf.index', [
            'breadcrumbs' => $breadcrumbs,
            'type' => $type,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('template.spaf.index'), 'name'=>"SPAF Templates"], ['name'=>"Create New"]
        ];
        return view('app.template.spaf.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            // $data['type'] = 'spaf';
            $template = Template::create($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Template added successfully!',
                        'redirect' => route('template.spaf.edit', $template)
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
     * Display the specified resource.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        return view('app.template.spaf.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('template.spaf.index', ['type' => $template->type]), 'name'=>"List Templates"], ['name'=> $template->name]
        ];
        return view('app.template.spaf.edit', compact('template', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        try {
            DB::beginTransaction();
            $template->update(['is_deleted' => true]);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Template successfully deleted!'
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

    public function changeStatus(Template $template)
    {
        try {
            DB::beginTransaction();
            $template->update(['status' => $template->status ? false : true]);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Template successfully changed status!'
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

    public function delete(Template $template){
        $action = route('template.spaf.destroy', ['template' => $template->id]);
        $title = 'template ' . $template->name;
        return view('layouts.delete', compact('action' , 'title'));
    }

    public function approve(Template $template){
        try {
            DB::beginTransaction();
            $template->update(['is_approved' => true]);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Template successfully approved!'
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

    public function clone(Template $template, Request $request){
        try {
            DB::beginTransaction();
            $data = $request->all();
            $newTemplate = $template->replicate();
            if($request->has('notes')){
                $newTemplate->name = $request->notes;
            }
            $newTemplate->is_approved = false;
            $newTemplate->push();
            foreach($template->groups as $group){
                $newGroup = $group->replicate();
                $newGroup->template_id = $newTemplate->id;
                $newGroup->push();
                $newGroup->questions()->createMany($group->questions->toArray());
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Template cloned successfully!',
                        'redirect' => route('template.spaf.edit', $newTemplate)
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

    public function preview(Template $template){
        return view('app.template.spaf.preview', compact('template'));
    }
}
