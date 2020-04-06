<?php

namespace App\Http\Controllers\Front\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $productQuery = Product::with('categories', 'brand')->active();

        if ($q = $request->query('q')) {
            $productQuery->where(function ($query) use ($q) {
                $query->where('name', 'ilike', "%{$q}%")
                    ->orWhereHas('brand', function ($brandQuery) use ($q) {
                        $brandQuery->where('brands.name', 'ilike', "%{$q}%");
                    })->orWhereHas('categories', function ($categoryQuery) use ($q) {
                        $categoryQuery->where('categories.name', 'ilike', "%{$q}%");
                    });
            });
        }

        $brand_ids = [];
        if ($request->query('brands')) {
            $brand_ids = explode(';', $request->query('brands'));
            $productQuery->whereIn('brand_id', $brand_ids);
        }

        $category_ids = [];
        if ($request->query('categories')) {
            $category_ids = explode(';', $request->query('categories'));
            $productQuery->whereHas('categories', function ($query) use ($category_ids) {
                $query->whereIn('categories.id', $category_ids)->orWhereIn('categories.parent_id', $category_ids);
            });
        }

        $sort = $request->query('sort');
        if ($sort == 'priceAsc') {
            $productQuery->orderBy('price');
        }

        if ($sort == 'priceDesc') {
            $productQuery->orderByDesc('price');
        }

        if ($sort == 'newest') {
            $productQuery->orderByDesc('created_at');
        }

        if ($sort == 'oldest') {
            $productQuery->orderBy('created_at');
        }

        $products = $productQuery->paginate($request->offset);

        $categories = Category::parents()->with('allChildren')->get();
        $brands = Brand::get();
        $query = [
            'categories' => Category::whereIn('id', $category_ids)->get(),
            'brands' => Brand::whereIn('id', $brand_ids)->get(),
            'q' => $request->query('q')
        ];

        return view('products.products', compact('products', 'categories', 'brands', 'query'));
    }

    public function show($id)
    {
        $product = Product::with('categories', 'brand.images')->active()->findOrFail($id);
        return view('products.product', compact('product'));
    }
}
