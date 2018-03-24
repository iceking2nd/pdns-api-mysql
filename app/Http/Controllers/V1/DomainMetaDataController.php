<?php

namespace App\Http\Controllers\V1;

use App\Models\DomainMetaData;
use App\Transformer\DomainMetaDataTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DomainMetaDataController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domainMetaData = DomainMetaData::paginate(10);
        return $this->response->paginator($domainMetaData,new DomainMetaDataTransformer());
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
                'kind' => [
                    'required',
                    Rule::in([
                        'ALLOW-AXFR-FROM','API-RECTIFY','AXFR-SOURCE',
                        'ALLOW-DNSUPDATE-FROM','TSIG-ALLOW-DNSUPDATE',
                        'FORWARD-DNSUPDATE','SOA-EDIT-DNSUPDATE','NOTIFY-DNSUPDATE',
                        'ALSO-NOTIFY','AXFR-MASTER-TSIG',
                        'GSS-ALLOW-AXFR-PRINCIPAL','GSS-ACCEPTOR-PRINCIPAL',
                        'IXFR','LUA-AXFR-SCRIPT','NSEC3NARROW','NSEC3PARAM',
                        'PRESIGNED','PUBLISH-CDNSKEY','PUBLISH-CDS','SOA-EDIT',
                        'SOA-EDIT-API','TSIG-ALLOW-AXFR','TSIG-ALLOW-DNSUPDATE'
                        ])
                ],
                'content' => 'required',
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                DomainMetaData::create((array)$data);
                return $this->response->created();
            }
            else
            {
                throw new StoreResourceFailedException('Could not create new DomainMetaData.',$validator->errors());
            }
        }
        else
        {
            throw new StoreResourceFailedException('DomainMetaData data is required.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DomainMetaData  $domainMetaData
     * @return \Illuminate\Http\Response
     */
    public function show(DomainMetaData $domainMetaData)
    {
        return $this->response->item($domainMetaData,new DomainMetaDataTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DomainMetaData  $domainMetaData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DomainMetaData $domainMetaData)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'domain_id' => 'sometimes|required|exists:domains,id',
                'kind' => [
                    'sometimes',
                    'required',
                    Rule::in([
                        'ALLOW-AXFR-FROM','API-RECTIFY','AXFR-SOURCE',
                        'ALLOW-DNSUPDATE-FROM','TSIG-ALLOW-DNSUPDATE',
                        'FORWARD-DNSUPDATE','SOA-EDIT-DNSUPDATE','NOTIFY-DNSUPDATE',
                        'ALSO-NOTIFY','AXFR-MASTER-TSIG',
                        'GSS-ALLOW-AXFR-PRINCIPAL','GSS-ACCEPTOR-PRINCIPAL',
                        'IXFR','LUA-AXFR-SCRIPT','NSEC3NARROW','NSEC3PARAM',
                        'PRESIGNED','PUBLISH-CDNSKEY','PUBLISH-CDS','SOA-EDIT',
                        'SOA-EDIT-API','TSIG-ALLOW-AXFR','TSIG-ALLOW-DNSUPDATE'
                    ])
                ],
                'content' => 'sometimes|required',
            ];
            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $domainMetaData->update($data);
                return $this->response->noContent();
            }
            else
            {
                throw new UpdateResourceFailedException('DomainMetaData was not updated.',$validator->errors());
            }
        }
        else
        {
            throw new UpdateResourceFailedException('DomainMetaData data is required.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DomainMetaData  $domainMetaData
     * @return \Illuminate\Http\Response
     */
    public function destroy(DomainMetaData $domainMetaData)
    {
        $domainMetaData->delete();
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
            'kind' => 'kind',
            'content' => 'content',
        ];
        if(array_key_exists($method,$transformer))
        {
            $domainMetaData = DomainMetaData::where($transformer[$method],'=',$data)->get();
            if (count($domainMetaData))
            {
                return $this->response->collection($domainMetaData,new DomainMetaDataTransformer());
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
