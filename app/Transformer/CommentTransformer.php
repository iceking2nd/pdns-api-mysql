<?php
/**
 * Created by PhpStorm.
 * User: iceki
 * Date: 2018/3/21
 * Time: 23:18
 */

namespace App\Transformer;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;


class CommentTransformer extends TransformerAbstract
{
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment['id'],
            'domain_id' => $comment['domain_id'],
            'name' => $comment['name'],
            'type' => $comment['type'],
            'modified_at' => $comment['modified_at'],
            'account' => $comment['account'],
            'comment' => $comment['comment']
        ];
    }
}