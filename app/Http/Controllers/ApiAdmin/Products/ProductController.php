<?php

namespace App\Http\Controllers\ApiAdmin\Products;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Imports\ProductImport;
use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $productQuery = Product::with('categories', 'brand')->latest();

        if ($q = $request->query('q')) {
            $productQuery->where('name', 'ilike', "%{$q}%");
        }

        if ($request->query('categories')) {
            $category_ids = explode(';', $request->query('categories'));
            $productQuery->whereHas('categories', function ($query) use ($category_ids) {
                $query->whereIn('categories.id', $category_ids);
            });
        }

        if ($request->query('paginate') == true) {
            return $productQuery->paginate($request->offset ?? 10);
        }

        return $productQuery->limit($request->limit)->get();
    }

    public function store(StoreProductRequest $request)
    {
        $brand = Brand::firstOrCreate(['name' => $request->brand]);
        $category_ids = $this->getCategoryIds($request);

        $product = new Product($request->all());
        $product->brand()->associate($brand);
        $product->save();

        $product->categories()->sync($category_ids);

        return $product;
    }

    public function show($id)
    {
        $product = Product::with('categories', 'brand')->findOrFail($id);

        return $product;
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $brand = Brand::firstOrCreate(['name' => $request->brand]);
        $category_ids = $this->getCategoryIds($request);

        $product->fill($request->all());
        $product->brand()->associate($brand);
        $product->save();

        $product->categories()->sync($category_ids);

        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);


        $product->delete();

        return response()->json(['message' => __('response.deleted')]);
    }

    protected function getCategoryIds(Request $request)
    {
        $category_ids = [];
        foreach ($request->categories as $request_item) {
            $category = Category::firstOrCreate(['name' => $request_item]);
            $category_ids[] = $category->id;
        }
        return $category_ids;
    }

    public function export()
    {
        $date = today()->format('d_m_Y');
        return Excel::download(new ProductExport(), "products_{$date}.xlsx");
    }

    public function import(Request $request)
    {
        $this->validate($request,[
            'products' => 'required|mimes:xlsx,xls'
        ]);
        Excel::import(new ProductImport(), $request->file('products'));
    }
}
