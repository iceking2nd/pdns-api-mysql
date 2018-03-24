<?php

namespace App\Http\Controllers\V1;

use App\Models\SuperMaster;
use App\Transformer\SuperMasterTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;

class SuperMastersController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supermasters = SuperMaster::paginate(10);
        return $this->response->paginator($supermasters,new SuperMasterTransformer());
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
                'ip' => 'required|ip',
                'nameserver' => 'required',
                'account' => 'required',
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                SuperMaster::create((array)$data);
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
     * @param  \App\Models\SuperMaster  $supermaster
     * @return \Illuminate\Http\Response
     */
    public function show(SuperMaster $supermaster)
    {
        return $this->response->item($supermaster,new SuperMasterTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SuperMaster  $supermaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuperMaster $supermaster)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'ip' => 'sometimes|required|ip',
                'nameserver' => 'sometimes|required',
                'account' => 'sometimes|required',
            ];
            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $supermaster->update($data);
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
     * @param  \App\Models\SuperMaster  $superMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuperMaster $supermaster)
    {
        $supermaster->delete();
        return $this->response->noContent();
    }
    public function GetByMethod($method, $data)
    {
        $transformer = [
            'id' => 'id',
            'ip' => 'ip',
            'nameserver' => 'nameserver',
            'account' => 'account',
        ];
        if(array_key_exists($method,$transformer))
        {
            $supermasters = SuperMaster::where($transformer[$method],'=',$data)->get();
            if (count($supermasters))
            {
                return $this->response->collection($supermasters,new SuperMasterTransformer());
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
