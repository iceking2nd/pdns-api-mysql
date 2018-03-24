<?php

namespace App\Http\Controllers\V1;

use App\Models\TSIGKey;
use App\Transformer\TSIGKeyTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;

class TSIGKeysController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tsigkeys = TSIGKey::paginate(10);
        return $this->response->paginator($tsigkeys,new TSIGKeyTransformer());
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
                'name' => 'required',
                'algorithm' => 'required',
                'secret' => 'required',
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                TSIGKey::create((array)$data);
                return $this->response->created();
            }
            else
            {
                throw new StoreResourceFailedException('Could not create new SuperMaster.',$validator->errors());
            }
        }
        else
        {
            throw new StoreResourceFailedException('SuperMaster data is required.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TSIGKey  $tsigkey
     * @return \Illuminate\Http\Response
     */
    public function show(TSIGKey $tsigkey)
    {
        return $this->response->item($tsigkey,new TSIGKeyTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TSIGKey  $tsigkey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TSIGKey $tsigkey)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'name' => 'sometimes|required',
                'algorithm' => 'sometimes|required',
                'secret' => 'sometimes|required',
            ];
            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $tsigkey->update($data);
                return $this->response->noContent();
            }
            else
            {
                throw new UpdateResourceFailedException('SuperMaster was not updated.',$validator->errors());
            }
        }
        else
        {
            throw new UpdateResourceFailedException('SuperMaster data is required.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TSIGKey  $tsigkey
     * @return \Illuminate\Http\Response
     */
    public function destroy(TSIGKey $tsigkey)
    {
        $tsigkey->delete();
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
            'name' => 'name',
            'algorithm' => 'algorithm',
            'secret' => 'secret',
        ];
        if(array_key_exists($method,$transformer))
        {
            $tsigkeys = TSIGKey::where($transformer[$method],'=',$data)->get();
            if (count($tsigkeys))
            {
                return $this->response->collection($tsigkeys,new TSIGKeyTransformer());
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
