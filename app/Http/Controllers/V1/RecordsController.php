<?php

namespace App\Http\Controllers\V1;

use App\Models\Record;
use App\Transformer\RecordTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RecordsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Record::paginate(10);
        return $this->response->paginator($records,new RecordTransformer());
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
                'type' => [
                    'required',
                    Rule::in([
                        'A','AAAA','AFSDB','ALIAS',
                        'CAA','CERT','CDNSKEY','CDS',
                        'CNAME','DNSKEY','DNAME','DS',
                        'HINFO','KEY','LOC','MX','NAPTR',
                        'NS','NSEC','NSEC3','NSEC3PARAM',
                        'OPENPGPKEY','PTR','RP','RRSIG',
                        'SOA','SPF','SSHFP','SRV','TKEY',
                        'TSIG','TLSA','SMIMEA','TXT','URI'
                    ]),
                ],
                'content' =>  'required',
                'ttl' =>  'numeric',
                'prio' =>  'numeric|required_if:type,==,MX',
                'change_date' =>  'numeric',
                'disabled' =>  'numeric',
                'auth' =>  'numeric',
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                if (!$this->checkConflictRecord($data['domain_id'],$data['name'],$data['type'],['A','CNAME']))
                {
                    Record::create((array)$data);
                    return $this->response->created();
                }
                else
                {
                    throw new StoreResourceFailedException('Record type conflict.');
                }
            }
            else
            {
                throw new StoreResourceFailedException('Could not create new Record.',$validator->errors());
            }
        }
        else
        {
            throw new StoreResourceFailedException('Record data is required.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function show(Record $record)
    {
        return $this->response->item($record,new RecordTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {
        $data = json_decode($request->instance()->getContent(),true);
        if (!is_null($data) && count($data) != 0)
        {
            $rule = [
                'domain_id' => 'sometimes|required|exists:domains,id',
                'name' => 'sometimes|required',
                'type' => [
                    'required',
                    Rule::in([
                        'A','AAAA','AFSDB','ALIAS',
                        'CAA','CERT','CDNSKEY','CDS',
                        'CNAME','DNSKEY','DNAME','DS',
                        'HINFO','KEY','LOC','MX','NAPTR',
                        'NS','NSEC','NSEC3','NSEC3PARAM',
                        'OPENPGPKEY','PTR','RP','RRSIG',
                        'SOA','SPF','SSHFP','SRV','TKEY',
                        'TSIG','TLSA','SMIMEA','TXT','URI'
                    ]),
                ],
                'content' =>  'sometimes|required',
                'ttl' =>  'sometimes|numeric',
                'prio' =>  'sometimes|numeric|required_if:type,==,MX',
                'change_date' =>  'sometimes|numeric',
                'disabled' =>  'sometimes|numeric',
                'auth' =>  'sometimes|numeric',
            ];

            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $record->update($data);
                return $this->response->noContent();
            }
            else
            {
                throw new UpdateResourceFailedException('Record was not updated.',$validator->errors());
            }
        }
        else
        {
            throw new UpdateResourceFailedException('Record data is required.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        $record->delete();
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
            'content' => 'content',
            'ttl' => 'ttl',
            'prio' => 'prio',
            'change_date' => 'change_date',
            'disabled' => 'disabled',
            'ordername' => 'ordername',
            'auth' => 'auth',
        ];
        if(array_key_exists($method,$transformer))
        {
            $records = Record::where($transformer[$method],'=',$data)->get();
            if (count($records))
            {
                return $this->response->collection($records,new RecordTransformer());
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

    private function checkConflictRecord($domain,$name,$type,array $rule) : bool
    {
        $r = $rule;
        $index = array_search($type,$r);
        if ( $index !== false )
        {
            unset($r[$index]);
            $records = Record::where('domain_id','=', $domain)->where('name','=',$name)->whereIn('type',$r)->get();
            if (count($records)) return true;
        }
        return false;
    }
}
