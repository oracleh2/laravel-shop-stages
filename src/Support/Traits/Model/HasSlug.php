<?php

namespace Support\Traits\Model;

use Illuminate\Database\Eloquent\Model;
use Stringable;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function (Model $model){
            $slug = $model->slug
                ?? str($model->{self::slugFrom()})
                    ->slug();

            $count = $model->newModelQuery()
                ->where('slug', $slug)
                ->count();
            if($count > 0) {
                $i = 1;
                while ($model->newModelQuery()
                    ->where('slug', "{$slug}-{$i}")
                    ->count() > 0)
                {
                    $i++;
                }
                $model->slug = (string)$slug . "-{$count}";
            }
            else
                $model->slug = (string) $slug;
        });
    }
    public static function slugFrom(): string
    {
        return 'title';
    }


}
