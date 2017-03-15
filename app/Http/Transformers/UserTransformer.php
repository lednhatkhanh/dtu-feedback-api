<?php
/**
 * Created by PhpStorm.
 * User: lednh
 * Date: 3/13/2017
 * Time: 2:47 PM
 */

namespace App\Http\Transformers;


use App\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'roles' => $user->roles->pluck('name'),
            'created_at' => Carbon::createFromFormat('Y-m-d H:m:s', $user->created_at)
                ->format('Y-m-d H:m'),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:m:s', $user->updated_at)
                ->format('Y-m-d H:m'),
        ];
    }
}