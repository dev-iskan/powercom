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
        $usersQuery = User::with('admin','operator')->latest();

        if ($request->query('paginate') == true) {
            return $usersQuery->paginate($request->offset ?? 10);
        }

        return $usersQuery->limit($request->limit)->get();
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create(array_merge($request->all(),[
            'password' => Hash::make($request->password)
        ]));
        return $user;
    }

    public function show($id)
    {
        $user = User::with('admin','operator')->findOrFail($id);
        return $user;
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(array_merge($request->all(),[
            'password' => Hash::make($request->password)
        ]));
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return response()->json(['message' => __('response.not_allowed')],400);
        }
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => __('response.deleted')]);
    }
}
