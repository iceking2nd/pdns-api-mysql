<?php

namespace App\Http\Controllers\V1;

use App\Models\Domain;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Transformer\DomainTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;

class DomainsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domain::paginate(10);
        return $this->response->paginator($domains,new DomainTransformer());
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
                'name' => 'required|unique:domains,name',
                'master' => 'required_if:type,==,SLAVE',
                'type' => [
                    'required',
                    Rule::in(['MASTER','SLAVE','NATIVE']),
                ],
                'account' => 'required'
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                Domain::create((array)$data);
                return $this->response->created();
            }
            else
            {
                throw new StoreResourceFailedException('Could not create new domain.',$validator->errors());
            }
        }
        else
        {
            throw new StoreResourceFailedException('Domain data is required.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return $this->response->item($domain,new DomainTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'name' => 'sometimes|required|unique:domains,name',
                'master' => 'required_if:type,==,SLAVE',
                'type' => [
                    'sometimes',
                    'required',
                    Rule::in(['MASTER','SLAVE','NATIVE']),
                ],
                'account' => 'sometimes|required'
            ];
            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $domain->update($data);
                return $this->response->noContent();
            }
            else
            {
                throw new UpdateResourceFailedException('Domain was not updated.',$validator->errors());
            }
        }
        else
        {
            throw new UpdateResourceFailedException('Domain data is required.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();
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
            'master' => 'master',
            'last_check' => 'last_check',
            'type' => 'type',
            'account' => 'account'
        ];
        if(array_key_exists($method,$transformer))
        {
            $domains = Domain::where($transformer[$method],'=',$data)->get();
            if (count($domains))
            {
                return $this->response->collection($domains,new DomainTransformer());
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
        $domains = Domain::all();
        return $this->response->collection($domains,new DomainTransformer());
    }
}
