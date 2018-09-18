<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\CandidatesCollection;
use Illuminate\Http\Request;
use Validator;
use App\Rules\CandidateStatusRule;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Api\ApiResponses;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = Candidate::where('id','>',0);

        if (request()->has('reviewed')) {
            $reviewed = request()->get('reviewed') == 'false' || request()->get('reviewed') == false ? false : true;
            $collection->where('reviewed',$reviewed);
        }

        $sort = request()->has('sort') && request()->get('sort')=='status' ? 'status' : 'date_applied';
        $order = request()->has('order') && request()->get('order')=='desc' ? 'DESC' : 'ASC';
        $collection->orderBy($sort, $order);
        
        return ApiResponses::respondCollectionRetrieved(new CandidatesCollection($collection->paginate(2)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'name' => 'required|max:255',
                'years_exp' => 'required|numeric|lte:50',
                'date_applied' => 'required|date',
                'description' => 'max:2000',
                'reviewed' => 'boolean',
            ]
        );  

        if ($validator->fails())
        {
           return ApiResponses::respondValidationFailed($validator->messages()->all());
        }
        return ApiResponses::respondCreated(new CandidateResource(Candidate::create($request->all())));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        return ApiResponses::respondRetrieved(new CandidateResource($candidate));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        $validator = Validator::make(array_merge($candidate->getAttributes(), $request->all()), 
            [
                'name' => 'required|max:255',
                'years_exp' => 'required|numeric|lte:50',
                'date_applied' => 'required|date',
                'description' => 'max:2000',
                'reviewed' => 'boolean',
                'status' => new CandidateStatusRule($request, $candidate)
            ]
        );  

        if ($validator->fails())
        {
           return ApiResponses::respondValidationFailed($validator->messages()->all());
        }

        $candidate->update($request->all());
        return ApiResponses::respondUpdated(new CandidateResource($candidate));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return ApiResponses::respondDeleted(new CandidateResource($candidate));
    }
}
