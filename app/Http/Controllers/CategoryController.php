<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function storeQuick(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        $category = Category::create(['name' => $request->name]);
        return response()->json($category);
    }

    public function json()
    {
        return Category::select('id', 'name')->get();
    }
}
