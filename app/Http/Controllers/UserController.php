<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function getAllVoters()
    {
        try {
            $voters = User::all();
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
    public function getVoter($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['msg' => 'No voter found', 'success' => false], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user
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

    function voterRegister(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6',
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
                            'message' => 'Please enter valid credentials',
                            'details' => $formattedErrors,
                        ]
                    ], 422
                );
            }

            $data = $request->all();
            $data['vid'] = rand(100000, 999999);
            $voter = User::create($data);
            // $voter->sendEmailVerificationNotification();
            return response()->json([
                'success' => true,
                'data' => $voter
            ], 201);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Voter registration failed.',
                        'details' => $e->errorInfo[2],
                        'code' => $e->getCode()
                    ]
                ], 404
            );
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    function voterUpdate(Request $request, $id)
    {
        try {
            $voter = User::find($id);

            $validator = Validator::make($request->all(), [
                'fname' => 'required',
                'lname' => 'required',
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
                            'message' => 'Please enter valid credentials',
                            'details' => $formattedErrors,
                        ]
                    ], 422
                );
            }

            $data = $request->all();

            $voter->update($data);

            return response()->json([
                'success' => true,
                'data' => $voter
            ], 201);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Failed to update.',
                        'details' => $e->errorInfo[2],
                        'code' => $e->getCode()
                    ]
                ], 404
            );
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    function voterUpdatePassword(Request $request, $id)
    {
        try {
            $voter = User::find($id);

            if (!$voter) {
                return response()->json(
                    [
                        'success' => false,
                        'error' => [
                            'message' => 'No resources were found',
                            'details' => null,
                        ]
                    ], 404
                );
            }

            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'current_password' => 'required',
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
                            'message' => 'Please enter valid credentials',
                            'details' => $formattedErrors,
                        ]
                    ], 422
                );
            }

            if (!Hash::check($request->current_password, $voter->password)) {
                return response()->json(
                    [
                        'success' => false,
                        'error' => [
                            'message' => 'The password you\'ve entered is incorrect',
                            'details' => null,
                        ]
                    ], 422
                );
            }

            $voter->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'data' => $voter
            ], 201);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Failed to change password.',
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
