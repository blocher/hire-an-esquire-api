<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Validator;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = \App\Models\Candidate::where('id','>',0);

        if (request()->has('reviewed')) {
            $reviewed = request()->get('reviewed') == 'false' || request()->get('reviewed') == false ? false : true;
            $collection->where('reviewed',$reviewed);
        }

        $sort = request()->has('sort') && request()->get('sort')=='status' ? 'status' : 'date_applied';
        $order = request()->has('order') && request()->get('order')=='desc' ? 'DESC' : 'ASC';
        $collection->orderBy($sort, $order);
        
        return new \App\Http\Resources\CandidatesCollection($collection->get());
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
           return response()->json(['message' => 'ERROR', 'errors' => $validator->messages()->all()], 404);
        }
        return new \App\Http\Resources\Candidate(\App\Models\Candidate::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        return new \App\Http\Resources\Candidate($candidate);
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
            ]
        );  

        if ($validator->fails())
        {
           return response()->json(['message' => 'ERROR', 'errors' => $validator->messages()->all()], 404);
        }
         $candidate->update($request->all());
         return new \App\Http\Resources\Candidate($candidate);
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

        return response()->json(['message' => 'Deleted'], 204);
    }
}
