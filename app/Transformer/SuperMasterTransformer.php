<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/25
 * Time: 2:30
 */

namespace App\Transformer;


use App\Models\SuperMaster;
use League\Fractal\TransformerAbstract;

class SuperMasterTransformer extends TransformerAbstract
{
    public function transform(SuperMaster $superMaster)
    {
        return [
            'id' => $superMaster['id'],
            'ip' => $superMaster['ip'],
            'nameserver' => $superMaster['nameserver'],
            'account' => $superMaster['account'],
        ];
    }
}