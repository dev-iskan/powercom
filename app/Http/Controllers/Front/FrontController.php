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

    public function article($id)
    {
        $article = Article::findOrFail($id);
        return view('article', compact('article'));
    }
}
