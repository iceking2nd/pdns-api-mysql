<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/24
 * Time: 14:19
 */

namespace App\Transformer;


use App\Models\Record;
use League\Fractal\TransformerAbstract;

class RecordTransformer extends TransformerAbstract
{
    public function transform(Record $record)
    {
        return [
            'id' => $record['id'],
            'domain_id' => $record['domain_id'],
            'name' => $record['name'],
            'type' => $record['type'],
            'content' => $record['content'],
            'ttl' => $record['ttl'],
            'prio' => $record['prio'],
            'change_date' => $record['change_date'],
            'disabled' => $record['disabled'],
            'ordername' => $record['ordername'],
            'auth' => $record['auth'],
        ];
    }
}