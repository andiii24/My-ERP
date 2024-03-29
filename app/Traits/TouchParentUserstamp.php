<?php

namespace App\Traits;

trait TouchParentUserstamp
{
    protected static function bootTouchParentUserstamp()
    {
        static::updating(function ($model) {
            if (auth()->check()) {
                $parentModel = $model->parentModel();

                $parentModel->updated_by = authUser()->id;

                $parentModel->save();
            }
        });
    }

    abstract public function parentModel();
}
