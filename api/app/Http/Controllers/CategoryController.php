<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse("All categories.", Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("create");
        $fields = $request->validate([
            "name" => ['required'],
        ]);

        $categoryExist = Category::where("name", $fields["name"])->first();
        if ($categoryExist) return $this->sendError("Category already ". $categoryExist->name ." exist with this name", [], 422);

        $category = Category::create($fields);
        return $this->sendResponse("Category created succesfully", $category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json(['category' => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize("update");
        $fields = $request->validate([
            "name" => ['required'],
        ]);

        $categoryExist = Category::where("name", $fields["name"])->first();
        if ($categoryExist) return $this->sendError("Category already exist with this name", [], 422);

        $category->update($fields);
        return $this->sendResponse("Category updated succefully", $category, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize("delete");
        $category->delete();
        return response()->json(['message' => 'Category deleted succefully'], 204);
    }
}
