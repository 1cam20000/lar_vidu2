<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /admin/categories
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    // GET /admin/categories/create
    public function create()
    {
        return view('admin.categories.create');
    }

    // POST /admin/categories
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Tạo danh mục thành công.');
    }

    // GET /admin/categories/{category}
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    // GET /admin/categories/{category}/edit
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // PUT/PATCH /admin/categories/{category}
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    // DELETE /admin/categories/{category}
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Xoá danh mục thành công.');
    }
}
