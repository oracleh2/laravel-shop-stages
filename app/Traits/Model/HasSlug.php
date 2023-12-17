<?php

namespace App\Traits\Model;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

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
                $model->slug = "{$slug}-{$count}";
            }
            else
                $model->slug = $slug;
        });
    }
    public static function slugFrom(): string
    {
        return 'title';
    }


}
