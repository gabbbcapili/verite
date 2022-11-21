<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\Question;
use Validator;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Template $template)
    {
        return view('app.template.group.create', compact('template'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Template $template)
    {
        $validator = Validator::make($request->all(), [
            'header' => 'required',
            'question' => ['required', 'min:1']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            // if(! $request->user()->can('template.approve')){
                $template->update(['is_approved' => false]);
            // }
            $sort = $template->groups()->orderBy('sort', 'desc')->first() ? $template->groups()->orderBy('sort', 'desc')->first()->sort + 1 : 0;
            $group = $template->groups()->create([
                'header' => $request->header,
                'sort' => $sort
            ]);
            $count = 0;
            foreach($request->question as $q){
                $q['sort'] = $count;
                $count+= 1;
                $question = $group->questions()->create($q);
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Question Group successfully created!',
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('app.template.group.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'header' => 'required',
            'question' => ['required', 'min:1']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $group->update(['header' => $request->header]);
            $count = 0;
            foreach($request->question as $q){
                $q['sort'] = $count;
                $count+= 1;
                $question = $group->questions()->create($q);
            }

            $count = 0;
            $newQuestions = [];
            $updatedQuestions = [];
            foreach($request->question as $q){
                $q['sort'] = $count;
                $count+= 1;
                if($q['type'] == 'title'){
                    $q['required'] = false;
                    $q['next_line'] = true;
                }
                if(isset($q['question_id'])){
                    $updateQuestion = Question::findOrFail($q['question_id']);
                    $updatedQuestions[] = $updateQuestion->id;
                    $updateQuestion->update($q);
                }else{
                    $question = new Question($q);
                    $newQuestions[] = $question;
                }
            }
            $deleteQuestions = Question::where('group_id', $group->id)
                            ->whereNotIn('id', $updatedQuestions)
                            ->delete();
            if (!empty($newQuestions)) {
                $group->questions()->saveMany($newQuestions);
             }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Question Group successfully created!',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        try {
            DB::beginTransaction();
            $group->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Group Questions successfully deleted!'
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

    public function delete(Group $group){
        $action = route('template.group.destroy', $group->id);
        $title = 'group ' . $group->header;
        return view('layouts.delete', compact('action' , 'title'));
    }

    public function preview(Template $template){
        return view('app.template.group.preview', compact('template'));
    }

    public function updateSort(Template $template, Request $request){
        $groups = $template->groups;
        foreach($request->sort as $key => $sort){
            $group = $groups->find($sort);
            if ($group != null){
                $group->update(['sort' => $key += 1]);
            }
        }
    }

    public function clone(Group $group, Request $request){
        try {
            DB::beginTransaction();
            $data = $request->all();
            $newGroup = $group->replicate();
            $newGroup->push();
            $newGroup->questions()->createMany($group->questions->toArray());
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Template cloned successfully!',
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
