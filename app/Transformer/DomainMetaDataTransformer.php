<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/24
 * Time: 13:37
 */

namespace App\Transformer;


use App\Models\DomainMetaData;

class DomainMetaDataTransformer extends TransformerAbstract
{
    public function transform(DomainMetaData $domainMetaData)
    {
        return [
            'id' => $domainMetaData['id'],
            'domain_id' => $domainMetaData['domain_id'],
            'kind' => $domainMetaData['kind'],
            'content' => $domainMetaData['content'],
        ];
    }
}