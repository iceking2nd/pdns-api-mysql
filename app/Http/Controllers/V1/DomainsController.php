<?php

namespace App\Http\Controllers\V1;

use App\Models\Domain;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Transformer\DomainTransformer;

class DomainsController extends APIController
{
    protected $transformer;

    public function __construct(DomainTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domain::all();
        return $this->responseWithData($this->transformer->transformCollection($domains->toArray()));
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
        if (!is_null($data))
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
                $domain = Domain::create((array)$data);
                return $this->responseWithCreatedData($domain->toArray());
            }
            else
            {
                return $this->responseErrorParameters($validator->errors()->getMessages());
            }
        }
        return $this->responseErrorParameters();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return $this->responseWithData($this->transformer->transform($domain->toArray()));
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
        if (!is_null($data))
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
                return $this->responseWithUpdatedData($domain->toArray());
            }
            else
            {
                return $this->responseErrorParameters($validator->errors()->getMessages());
            }
        }
        return $this->responseErrorParameters();
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
        return $this->responseSuccessWithoutData();
    }
}
