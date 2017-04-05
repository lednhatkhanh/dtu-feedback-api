<?php
/**
 * Created by PhpStorm.
 * User: lednh
 * Date: 4/5/2017
 * Time: 4:23 PM
 */

namespace App\Http\Transformers;


use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $category) {
        return [
            'id' => $category->id,
            'name' => $category->name,
        ];
    }
}