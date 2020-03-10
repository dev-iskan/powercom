<?php

namespace App\Http\Controllers\Front\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($id, Request $request)
    {
        $category = Category::findOrFail($id);
        /** @var BelongsToMany $productQuery */
        $productQuery = $category->products()->active()->with('categories', 'brand');

        if ($request->query('brands')) {
            $brand_ids = explode(';', $request->query('brands'));
            $productQuery->whereIn('brand_id', $brand_ids);
        }

        if ($request->query('categories')) {
            $category_ids = explode(';', $request->query('categories'));
            $productQuery->whereHas('categories', function ($query) use ($category_ids) {
                $query->whereIn('categories.id', $category_ids);
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

        return view('category', compact('category', 'products'));
    }
}
