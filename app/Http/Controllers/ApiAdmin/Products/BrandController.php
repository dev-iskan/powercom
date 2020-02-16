<?php

namespace App\Http\Controllers\ApiAdmin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brands\StoreBrandRequest;
use App\Models\Products\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brandsQuery = Brand::latest();

        if ($q = $request->query('q')) {
            $brandsQuery->where('name', 'like', '%' . $q . '%');
        }

        if ($request->query('paginate') == true) {
            return $brandsQuery->paginate($request->offset ?? 10);
        }

        return $brandsQuery->limit($request->limit ?? null)->get();
    }

    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->all());
        return $brand;
    }

    public function show($id)
    {

    }

    public function update(StoreBrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($request->all());

        return $brand;
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->json(['message' => __('response.deleted')]);
    }
}
