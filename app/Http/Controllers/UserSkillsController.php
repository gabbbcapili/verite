<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientPreference;
use App\Models\Skills;

class UserSkillsController extends Controller
{
    public function index(User $user){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('user.index'), 'name'=>"Users"], ['name'=>"Skills & Proficiency"]
        ];
        return view('app.user.skills.index', compact('user', 'breadcrumbs'));
    }

    public function create(){
    }
}
