<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/21
 * Time: 23:07
 */

namespace App\Transformer;


abstract class Transformer
{
    public function transformCollection($items)
    {
        return array_map([$this,'transform'],$items);
    }

    public abstract function transform($item);
}