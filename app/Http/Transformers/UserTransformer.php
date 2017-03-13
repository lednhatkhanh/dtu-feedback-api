<?php
/**
 * Created by PhpStorm.
 * User: lednh
 * Date: 3/13/2017
 * Time: 2:47 PM
 */

namespace App\Http\Transformers;


use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user) {
        return $user;
    }
}