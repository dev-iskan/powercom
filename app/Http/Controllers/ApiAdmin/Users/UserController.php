<?php

namespace App\Http\Controllers\ApiAdmin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $usersQuery = User::latest();

        $client_query = $request->query('client');
        $admin_query = $request->query('admin');
        $operator_query = $request->query('operator');

        if ($admin_query === '1') {
            $usersQuery->has('admin');
        } elseif ($admin_query === '0') {
            $usersQuery->doesntHave('admin');
        }

        if ($operator_query === '1') {
            $usersQuery->has('operator');
        } elseif ($operator_query === '0') {
            $usersQuery->doesntHave('operator');
        }

        if ($client_query === '1') {
            $usersQuery->has('client');
        } elseif ($client_query === '0') {
            $usersQuery->doesntHave('client');
        }

        if ($q = $request->query('q')) {
            $usersQuery->where(function ($query) use ($q) {
                $query->where('name', 'ilike', "%{$q}%")
                    ->orWhere('phone', 'ilike', "%{$q}%")
                    ->orWhere('email', 'ilike', "%{$q}%");
            });
        }

        if ($request->query('paginate') == true) {
            return $usersQuery->paginate($request->offset ?? 10);
        }

        return $usersQuery->limit($request->limit)->get();
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create(array_merge($request->all(), [
            'password' => Hash::make($request->password)
        ]));
        return $user;
    }

    public function show($id)
    {
        $user = User::with('admin', 'operator', 'client')->findOrFail($id);
        return $user;
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(array_merge($request->all(), [
            'password' => Hash::make($request->password)
        ]));
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return response()->json(['message' => __('response.not_allowed')], 400);
        }
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => __('response.deleted')]);
    }

    public function toggleAdmin($id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            $user->admin()->delete();
        } else {
            $user->admin()->create();
        }

        return $user;
    }

    public function toggleOperator($id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        if ($user->isOperator()) {
            $user->operator()->delete();
        } else {
            $user->operator()->create();
        }

        return $user;
    }
}
