<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
 

public function register(Request $request)
{
    if (! $request->user()->tokenCan('admin')) {
    return response()->json(['message' => 'Forbidden'], 403);
}
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required','string','email','max:255','unique:employees'],
        'password' => 'required|string|min:8|confirmed',
        'role' => ['required', Rule::in([Employee::ROLE_USER, Employee::ROLE_ADMIN])],
    ]);

    $employee = Employee::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
    ]);

    $token = $employee->createToken('api-token', $employee->role === Employee::ROLE_ADMIN ? ['admin'] : [])->plainTextToken;

    return response()->json([
        'message' => 'Registered',
        'employee' => $employee,
        'token' => $token,
    ], 201);
}

public function login(Request $request)
{
    $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ]);

    $employee = Employee::where('email', $request->email)->first();

    if (! $employee || ! Hash::check($request->password, $employee->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // give admin tokens the 'admin' ability
    $abilities = $employee->role === Employee::ROLE_ADMIN ? ['admin'] : [];

    $token = $employee->createToken('api-token', $abilities)->plainTextToken;

    return response()->json([
        'message' => 'Logged in',
        'employee' => $employee,
        'token' => $token,
    ]);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
 
        return response()->json(['message' => 'Employees retrieved successfully', 'employees' => Employee::all()], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
   if (! $this->isAdmin($request)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
       

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in([Employee::ROLE_USER, Employee::ROLE_ADMIN])],
        ]);
        $employee = Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        $token = $employee->createToken('api-token', $employee->role === Employee::ROLE_ADMIN ? ['admin'] : [])->plainTextToken;
        

        return response()->json(['message' => 'Employee created successfully', 'employee' => $employee, 'token' => $token], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
     
        return response()->json(['message' => 'Employee retrieved successfully', 'employee' => $employee], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:employees,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $employee = Employee::findOrFail($id);
        if ($request->has('name')) {
            $employee->name = $request->name;
        }
        if ($request->has('email')) {
            $employee->email = $request->email;
        }
        if ($request->has('password')) {
            $employee->password = bcrypt($request->password);
        }
        $employee->save();


       return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
{
    // Validate the request
    $request->validate([
        'email' => 'required|email',
        'oldPassword' => 'required|string',
        'newPassword' => 'required|string|min:8|confirmed',
    ]);

    // Get the authenticated user
    $employee = $request->user();

    // Check that the email matches the authenticated user
    if ($employee->email !== $request->email) {
        return response()->json(['message' => 'Email does not match authenticated user'], 403);
    }

    // Verify old password
    if (! Hash::check($request->oldPassword, $employee->password)) {
        return response()->json(['message' => 'Old password is incorrect'], 400);
    }

    // Update with new password
    $employee->password = Hash::make($request->newPassword);
    $employee->save();

    // Optional: Invalidate all other tokens (forces re-login)
    $employee->tokens()->delete();

     return response()->json([
        'message' => 'Password updated successfully. Please log in again.'
    ], 200);
}


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        
        return response()->json(['message' => 'Employee deleted successfully'], 204);
    }
    private function isAdmin(Request $request): bool
    {
        $user = $request->user();
        return $user
            && $user->role === Employee::ROLE_ADMIN
            && $user->tokenCan('admin');
    }
     public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    // Step 2: Handle reset with token
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }
}
