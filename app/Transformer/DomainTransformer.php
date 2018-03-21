<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/21
 * Time: 23:18
 */

namespace App\Transformer;


class DomainTransformer extends Transformer
{
    public function transform($domain)
    {
        return [
            'domain_id' => $domain['id'],
            'domain_name' => $domain['name'],
            'master_server' => $domain['master'],
            'last_check_time' => $domain['last_check'],
            'zone_type' => $domain['type'],
            'maintenance_account' => $domain['account']
        ];
    }
}