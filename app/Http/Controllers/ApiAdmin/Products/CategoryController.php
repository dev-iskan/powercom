<?php

namespace App\Http\Controllers\ApiAdmin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Models\Products\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categoriesQuery = Category::latest();

        if ($q = $request->query('q')) {
            $categoriesQuery->where('name', 'ilike', "%{$q}%");
        }

        if ($request->query('type') == 'parent') {
            $categoriesQuery->parents();
        } elseif ($request->query('type') == 'children') {
            $categoriesQuery->children();
        }

        if ($request->query('paginate') == true) {
            return $categoriesQuery->paginate($request->offset ?? 10);
        }

        return $categoriesQuery->limit($request->limit)->get();
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());
        $category->setParent($request->parent_id);

        return $category;
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    public function update(StoreCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        $category->setParent($request->parent_id);

        return $category;
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => __('response.deleted')]);
    }
}
