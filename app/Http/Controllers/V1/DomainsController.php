<?php

namespace App\Http\Controllers\V1;

use App\Models\Domain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DomainsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domain::all();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $domains->toArray()
        ]);
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
                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $domain->toArray(),
                ],201);
            }
            else
            {
                return response()->json([
                    'code' => 400,
                    'status' => 'failed',
                    'message' => $validator->errors()->getMessages(),
                    'data' => null,
                ],400);
            }
        }
        return response()->json([
            'code' => 400,
            'status' => 'failed',
            'message' => 'Invalid parameters',
            'data' => null,
        ],400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $domain->toArray()
        ]);
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
                'master' => 'required_if:type,==,SLAVE',
            ];
            $validator = Validator::make($data,$rule);
            if ($validator->passes())
            {
                $domain->update($data);
                return response()->json([
                    'code' => 204,
                    'status' => 'success',
                    'data' => $domain->toArray(),
                ],204);
            }
            else
            {
                return response()->json([
                    'code' => 400,
                    'status' => 'failed',
                    'message' => $validator->errors()->getMessages(),
                    'data' => null,
                ],400);
            }
        }
        return response()->json([
            'code' => 400,
            'status' => 'failed',
            'message' => 'Invalid parameters',
            'data' => null,
        ],400);
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
        return response()->json([],204);
    }
}
