<?php

namespace App\Http\Controllers\ApiAdmin\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Articles\StoreArticleRequest;
use App\Models\Blog\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articleQuery = Article::latest();

        if ($q = $request->query('q')) {
            $articleQuery->where('name', 'ilike', '%' . $q . '%');
        }

        if ($request->query('paginate') == true) {
            return $articleQuery->paginate($request->offset ?? 10);
        }

        return $articleQuery->limit($request->limit)->get();
    }

    public function store(StoreArticleRequest $request)
    {
        $article = Article::create($request->all());

        return $article;
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return $article;
    }

    public function update(StoreArticleRequest $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['message' => __('response.deleted')]);
    }
}
