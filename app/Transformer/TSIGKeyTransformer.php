<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/25
 * Time: 3:44
 */

namespace App\Transformer;


use App\Models\TSIGKey;
use League\Fractal\TransformerAbstract;

class TSIGKeyTransformer extends TransformerAbstract
{
    public function transform(TSIGKey $tsigkey)
    {
        return [
            'id' => $tsigkey['id'],
            'name' => $tsigkey['name'],
            'algorithm' => $tsigkey['algorithm'],
            'secret' => $tsigkey['secret'],
        ];
    }
}