<?php

namespace App\Http\Controllers\ApiAdmin\Clients;

use App\Http\Controllers\Controller;
use App\Models\Users\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clientQuery = Client::latest();

        if ($q = $request->query('q')) {
            $clientQuery->where(function ($query) use ($q) {
                $query->where('name', 'ilike', '%' . $q . '%')
                    ->orWhere('surname', 'ilike', '%' . $q . '%')
                    ->orWhere('patronymic', 'ilike', '%' . $q . '%')
                    ->orWhere('phone', 'ilike', '%' . $q . '%')
                    ->orWhere('email', 'ilike', '%' . $q . '%');
            });
        }

        if ($request->query('paginate') == true) {
            return $clientQuery->paginate($request->offset ?? 10);
        }

        return $clientQuery->limit($request->limit)->get();
    }


    public function show($id)
    {
        $client = Client::findOrFail($id);
        return $client;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'phone' => 'required|digits:12|unique:clients,phone,'.$id,
            'email' => 'required|email|max:255|unique:clients,email,'.$id,
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());
        return $client;
    }
}
