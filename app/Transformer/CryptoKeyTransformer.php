<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/24
 * Time: 13:09
 */

namespace App\Transformer;


use App\Models\CryptoKey;
use League\Fractal\TransformerAbstract;

class CryptoKeyTransformer extends TransformerAbstract
{
    public function transform(CryptoKey $cryptoKey)
    {
        return [
            'id' => $cryptoKey['id'],
            'domain_id' => $cryptoKey['domain_id'],
            'flags' => $cryptoKey['flags'],
            'active' => $cryptoKey['active'],
            'content' => $cryptoKey['content']
        ];
    }
}