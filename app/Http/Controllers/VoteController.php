<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'election_id' => 'required',
                'candidate_id' => 'required',
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

            $data = Vote::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $data
            ], 201);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Voting failed.',
                        'details' => $e->errorInfo[2],
                        'code' => $e->getCode()
                    ]
                ], 404
            );
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    function checkVote($eid, $id)
    {
        try {
            $vt = Vote::where('election_id', $eid)->where('user_id', $id)->first();
            return response()->json([
                'success' => true,
                'data' => $vt
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
}
