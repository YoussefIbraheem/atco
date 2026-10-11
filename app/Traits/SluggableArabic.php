<?php

namespace App\Traits;

use App\Helpers\ArabicSlugger;
use Illuminate\Database\Eloquent\Model;
use LogicException;

trait SluggableArabic
{
    protected static function bootSluggableArabic()
    {

        static::saving(function (Model $model) {

            if (!$model->sluggable()) {
                throw new LogicException("Model requires having $ sluggable attribute containing 'source' and 'target'");
            }

            $sluggable = $model->sluggable();

            if (!isset($sluggable["source"]) || !isset($sluggable["target"])) {
                throw new LogicException("Sluggable requires containing 'source' and 'target' attributes");
            }

            $source = $sluggable["source"];
            $target = $sluggable["target"];

            if (! in_array($source, $model->getFillable(), true)) {
                throw new LogicException(
                    "The slug source [{$source}] must be fillable."
                );
            }

            if (! in_array($target, $model->getFillable(), true)) {
                throw new LogicException(
                    "The slug target [{$target}] must be fillable."
                );
            }

            if ($model->isDirty($source) || blank($model->{$target})) {
                $model->{$target} = ArabicSlugger::handle(
                    $model->{$source}
                );
            }
        });
    }
}
