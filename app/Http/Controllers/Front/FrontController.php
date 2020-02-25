<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use App\Models\Products\Category;
use App\Models\Blog\Article;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function main()
    {
        $products = Product::with('categories', 'brand')->latest()->limit(8)->get();
        $articles = Article::latest()->get();
        return view('main', compact('products', 'articles'));
    }

    public function about()
    {
        return view('about');
    }

    public function publicOffer()
    {
        return view('public-offer');
    }

    public function category($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::with('categories', 'brand')->latest()->get();
        if ($category) {
            $category->children();
            $category->parent();
        }
        return view('category', compact('category', 'products'));
    }

    public function product($id)
    {
        $product = Product::with('categories', 'brand', 'files')->findOrFail($id);
        return view('product', compact('product'));
    }
}
