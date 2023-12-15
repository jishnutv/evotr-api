<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $voters = Election::with('candidates')->get();
            return response()->json([
                'success' => true,
                'data' => $voters
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
                'title' => 'required',
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

            $data = Election::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $data
            ], 201);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Failed to create a new election',
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Election::find($id);

            if (!$data) {
                return response()->json(
                    [
                        'success' => false,
                        'error' => [
                            'message' => 'No election found',
                            'details' => null,
                            'code' => null
                        ]
                    ], 404
                );
            }

            return response()->json([
                'success' => true,
                'data' => $data
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

        }
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
            $election = Election::find($id);

            if (!$election) {
                return response()->json(
                    [
                        'success' => false,
                        'error' => [
                            'message' => 'No election found',
                            'details' => null,
                            'code' => null
                        ]
                    ], 404
                );
            }

            $election->delete();

            return response()->json(
                [
                    'success' => true,
                    'data' => $election
                ], 200
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
