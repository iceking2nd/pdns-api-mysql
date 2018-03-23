<?php

namespace App\Http\Controllers\V1;

use App\Models\Comment;
use App\Transformer\CommentTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

class CommentsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::paginate(10);
        return $this->response->paginator($comments,new CommentTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'domain_id' => 'required|exists:domains,id',
                'name' => 'required',
                'type' => 'required',
                'account' => 'required',
                'comment' => 'required'
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $data = array_merge($data,['modified_at' => time()]);
                Comment::create((array)$data);
                return $this->response->created();
            }
            else
            {
                throw new StoreResourceFailedException('Could not create new comment.',$validator->errors());
            }
        }
        else
        {
            throw new StoreResourceFailedException('Comment data is required.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return $this->response->item($comment,new CommentTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'domain_id' => 'sometimes|required|exists:domains,id',
                'name' => 'sometimes|required',
                'type' => 'sometimes|required',
                'account' => 'sometimes|required',
                'comment' => 'sometimes|required'
            ];
            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $data = array_merge($data,['modified_at' => time()]);
                $comment->update($data);
                return $this->response->noContent();
            }
            else
            {
                throw new UpdateResourceFailedException('Comment was not updated.',$validator->errors());
            }
        }
        else
        {
            throw new UpdateResourceFailedException('Comment data is required.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->response->noContent();
    }

    /**
     * @param $method
     * @param $data
     * @return \Dingo\Api\Http\Response
     */
    public function GetByMethod($method, $data)
    {
        $transformer = [
            'id' => 'id',
            'domain_id' => 'domain_id',
            'name' => 'name',
            'type' => 'type',
            'modified_at' => 'modified_at',
            'account' => 'account',
            'comment' => 'comment'
        ];
        if(array_key_exists($method,$transformer))
        {
            $comments = Comment::where($transformer[$method],'=',$data)->get();
            if (count($comments))
            {
                return $this->response->collection($comments,new CommentTransformer());
            }
            else
            {
                $this->response->errorNotFound();
            }
        }
        else
        {
            $this->response->errorBadRequest('Invalid query method');
        }
    }
}
