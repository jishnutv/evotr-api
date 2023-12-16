<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $c = Candidate::where('election_id', $id)->get();
            return response()->json([
                'success' => true,
                'data' => $c
            ], 200);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'The requested resource could not be found.',
                        'details' => $e->errorInfo[2],
                        'code' => $e->getCode()
                    ]
                ], 404
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'election_id' => 'required',
                'fname' => 'required',
                'lname' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $formattedErrors = [];

                foreach ($errors as $field => $messages) {
                    $formattedErrors[$field] = $messages[0];
                }

                return response()->json(
                    [
                        'success' => false,
                        'error' => [
                            'message' => 'Please enter valid data.',
                            'details' => $formattedErrors,
                        ]
                    ], 422
                );
            }

            $data = Candidate::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $data
            ], 201);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Failed to create a new candidate.',
                        'details' => $e->errorInfo[2],
                        'code' => $e->getCode()
                    ]
                ], 404
            );
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $c = Candidate::find($id);

            if (!$c) {
                return response()->json(
                    [
                        'success' => false,
                        'error' => [
                            'message' => 'No candidates found',
                            'details' => null,
                            'code' => null
                        ]
                    ], 404
                );
            }

            $c->delete();

            return response()->json(
                [
                    'success' => true,
                    'data' => $c
                ], 200
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
