<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Models\Employee;

trait AuthorizesEmployee
{
    protected function isAdmin(Request $request): bool
    {
        $user = $request->user();
        return $user
            && $user->role === Employee::ROLE_ADMIN
            && $user->tokenCan('admin');
    }

    protected function forbidIfNotAdmin(Request $request)
    {
        if (! $this->isAdmin($request)) {
            abort(response()->json(['message' => 'Forbidden'], 403));
        }
    }

    protected function forbidIfNotSelfOrAdmin(Request $request, $targetEmployeeId)
    {
        $user = $request->user();
        if ($user->role !== Employee::ROLE_ADMIN && $user->id != $targetEmployeeId) {
            abort(response()->json(['message' => 'Forbidden'], 403));
        }
    }
}
