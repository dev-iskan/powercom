<?php

namespace App\Http\Controllers\ApiAdmin\Clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\StoreClientRequest;
use App\Http\Requests\Clients\UpdateClientRequest;
use App\Models\Users\Client;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clientQuery = Client::latest();

        if ($q = $request->query('q')) {
            $clientQuery->where(function ($query) use ($q) {
                $query->where('name', 'ilike', "%{$q}%")
                    ->orWhere('surname', 'ilike', "%{$q}%")
                    ->orWhere('patronymic', 'ilike', "%{$q}%")
                    ->orWhere('phone', 'ilike', "%{$q}%")
                    ->orWhere('email', 'ilike', "%{$q}%");
            });
        }

        if ($request->query('paginate') == true) {
            return $clientQuery->paginate($request->offset ?? 10);
        }

        return $clientQuery->limit($request->limit)->get();
    }

    public function store(StoreClientRequest $request)
    {
        $client = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name .' '. $request->surname.' '. $request->patronymic,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->phone)
            ]);

            return $user->client()->create($request->all());

        });

        return $client;
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return $client;
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->all());
        return $client;
    }
}
