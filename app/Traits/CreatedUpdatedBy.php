<?php

namespace App\Traits;

trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = is_object(\Auth::guard(config('app.guards.web'))->user()) ? \Auth::guard(config('app.guards.web'))->user()->id : null;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = is_object(\Auth::guard(config('app.guards.web'))->user()) ? \Auth::guard(config('app.guards.web'))->user()->id : null;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = is_object(\Auth::guard(config('app.guards.web'))->user()) ? \Auth::guard(config('app.guards.web'))->user()->id : null;
            }
        });
    }
}
