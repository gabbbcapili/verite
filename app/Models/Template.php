<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedBy;

class Template extends Model
{
    use HasFactory;
    use CreatedUpdatedBy;

    protected $table = 'template';

    protected $fillable = ['name', 'type', 'is_deleted', 'is_approved', 'status'];

    public static $typeList = ['spaf', 'spaf_extension', 'risk_management'];

    public function created_by_user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedByNameAttribute(){
        return $this->created_by_user ?  $this->created_by_user->fullName : null;
    }

    public function getUpdatedByNameAttribute(){
        return $this->updated_by_user ? $this->updated_by_user->fullName : null;
    }

    public function groups(){
        return $this->hasMany(Group::class, 'template_id');
    }

    public function questions(){
        return $this->hasManyThrough(Question::class, Group::class);
    }

    public function spaf(){
        return $this->hasOne(Spaf::class, 'user_id');
    }

    public function getTypeDisplayAttribute(){
        return strtoupper(str_replace('_', ' ', $this->type));
    }

    public function getStatusTextAttribute(){
        return $this->status ? 'Inactive' : 'Active';
    }

    public function createDefault(){
        $group = $this->groups()->create(['header' => 'SPAF Basic Information', 'displayed_on_schedule' => true, 'sort' => 0, 'editable' => 0]);

        $group->questions()->create([
            'text' => 'Facility Name:',
            'type' => 'input',
            'next_line' => 1,
            'sort' => 0,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Point Person for Coordination on Overall Assessment Requirements',
            'type' => 'title',
            'next_line' => 1,
            'sort' => 1,
            'required' => 0,
        ]);
        $group->questions()->create([
            'text' => 'Name:',
            'type' => 'input',
            'next_line' => 0,
            'sort' => 2,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Email:',
            'type' => 'email',
            'next_line' => 0,
            'sort' => 3,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Supplier Facility Characteristics',
            'type' => 'title',
            'next_line' => 1,
            'sort' => 4,
            'required' => 0,
        ]);
        $group->questions()->create([
            'text' => 'This facility produces:',
            'type' => 'input',
            'next_line' => 1,
            'sort' => 5,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Directly Employed Production Workers:',
            'type' => 'number',
            'next_line' => 1,
            'sort' => 6,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Contract Workers (not an employee of the company):',
            'type' => 'number',
            'next_line' => 1,
            'sort' => 7,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Temporary Workers:',
            'type' => 'number',
            'next_line' => 1,
            'sort' => 8,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Security Staff:',
            'type' => 'number',
            'next_line' => 1,
            'sort' => 9,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Cleaning Staff:',
            'type' => 'number',
            'next_line' => 1,
            'sort' => 10,
            'required' => 1,
        ]);
        $group->questions()->create([
            'text' => 'Canteen Workers:',
            'type' => 'number',
            'next_line' => 1,
            'sort' => 11,
            'required' => 1,
        ]);

        $group->questions()->create([
            'text' => 'Nationalities',
            'type' => 'table',
            'for_checkbox' => 'Nationality|Direct # of Workers',
            'next_line' => 1,
            'sort' => 12,
            'required' => 1,
        ]);

        $group->questions()->create([
            'text' => 'Dispatch Local Labor Agents:',
            'type' => 'title',
            'next_line' => 1,
            'sort' => 13,
            'required' => 0,
        ]);
        $group->questions()->create([
            'text' => 'Name:',
            'type' => 'input',
            'next_line' => 0,
            'sort' => 14,
            'required' => 0,
        ]);
        $group->questions()->create([
            'text' => 'Outsource Local Labor Agents:',
            'type' => 'title',
            'next_line' => 1,
            'sort' => 15,
            'required' => 0,
        ]);

        $group->questions()->create([
            'text' => 'Name:',
            'type' => 'input',
            'next_line' => 0,
            'sort' => 16,
            'required' => 0,
        ]);
        $group->questions()->create([
            'text' => 'Foreign Labor Agents:',
            'type' => 'title',
            'next_line' => 1,
            'sort' => 17,
            'required' => 0,
        ]);
        $group->questions()->create([
            'text' => 'Name:',
            'type' => 'input',
            'next_line' => 0,
            'sort' => 18,
            'required' => 0,
        ]);
        $group->questions()->create([
            'text' => 'How many Dormitory Buildings or Housing units are there?',
            'type' => 'title',
            'next_line' => 1,
            'sort' => 19,
            'required' => 0,
        ]);

        $group->questions()->create([
            'text' => 'Local workers:',
            'type' => 'number',
            'next_line' => 0,
            'sort' => 20,
            'required' => 0,
        ]);

        $group->questions()->create([
            'text' => 'Foreign contract workers:',
            'type' => 'number',
            'next_line' => 0,
            'sort' => 21,
            'required' => 0,
        ]);
    }

}
