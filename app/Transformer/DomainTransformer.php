<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/21
 * Time: 23:18
 */

namespace App\Transformer;

use App\Models\Domain;
use League\Fractal\TransformerAbstract;


class DomainTransformer extends TransformerAbstract
{
    public function transform(Domain $domain)
    {
        return [
            'id' => $domain['id'],
            'name' => $domain['name'],
            'master' => $domain['master'],
            'last_check' => $domain['last_check'],
            'type' => $domain['type'],
            'account' => $domain['account']
        ];
    }
}