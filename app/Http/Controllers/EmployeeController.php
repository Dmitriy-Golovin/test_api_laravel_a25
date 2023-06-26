<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function add(AddEmployeeRequest $request)
    {
        try {
            $data = $request->all();
            $user = new User();
            $user->password = Hash::make($data['password']);
            $user->email = $data['email'];
            $user->name = User::DEFAULT_NAME;
            $user->save();

            $employee = new Employee();
            $employee->user_id = $user->id;
            $employee->save();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $employee->serialize(),
            'message' => 'Success'
        ], JsonResponse::HTTP_CREATED);
    }

}
