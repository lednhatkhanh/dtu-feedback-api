<?php
/**
 * Created by PhpStorm.
 * User: lednh
 * Date: 3/8/2017
 * Time: 9:23 PM
 */

namespace App\Http\Transformers;


use App\Campus;
use League\Fractal\TransformerAbstract;

class CampusTransformer extends TransformerAbstract
{
    public function transform(Campus $campus) {
        return [
            'id' => $campus->id,
            'name' => $campus->name,
            'address' => $campus->address,
        ];
    }
}