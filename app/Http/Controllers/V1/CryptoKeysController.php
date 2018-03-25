<?php

namespace App\Http\Controllers\V1;

use App\Models\CryptoKey;
use App\Transformer\CryptoKeyTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;

class CryptoKeysController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cryptokeys = CryptoKey::paginate(10);
        return $this->response->paginator($cryptokeys,new CryptoKeyTransformer());
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
                'flags' => 'required|numeric',
                'active' => 'required|boolean',
                'content' => 'required',
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                CryptoKey::create((array)$data);
                return $this->response->created();
            }
            else
            {
                throw new StoreResourceFailedException('Could not create new CryptoKey.',$validator->errors());
            }
        }
        else
        {
            throw new StoreResourceFailedException('CryptoKey data is required.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CryptoKey  $cryptoKey
     * @return \Illuminate\Http\Response
     */
    public function show(CryptoKey $cryptoKey)
    {
        return $this->response->item($cryptoKey,new CryptoKeyTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CryptoKey  $cryptoKey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CryptoKey $cryptoKey)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'domain_id' => 'sometimes|required|exists:domains,id',
                'flags' => 'sometimes|required|numeric',
                'active' => 'sometimes|required|boolean',
                'content' => 'sometimes|required',
            ];
            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $cryptoKey->update($data);
                return $this->response->noContent();
            }
            else
            {
                throw new UpdateResourceFailedException('CryptoKey was not updated.',$validator->errors());
            }
        }
        else
        {
            throw new UpdateResourceFailedException('CryptoKey data is required.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CryptoKey  $cryptoKey
     * @return \Illuminate\Http\Response
     */
    public function destroy(CryptoKey $cryptoKey)
    {
        $cryptoKey->delete();
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
            'flags' => 'flags',
            'active' => 'active',
            'content' => 'content'
        ];
        if(array_key_exists($method,$transformer))
        {
            $cryptoKey = CryptoKey::where($transformer[$method],'=',$data)->get();
            if (count($cryptoKey))
            {
                return $this->response->collection($cryptoKey,new CryptoKeyTransformer());
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

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function list()
    {
        $cryptos = CryptoKey::all();
        return $this->response->collection($cryptos,new CryptoKeyTransformer());
    }
}
