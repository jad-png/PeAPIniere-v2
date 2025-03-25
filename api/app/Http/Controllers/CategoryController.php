<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    protected CategoryRepository $categoryRepository;

    public function __construct(categoryRepository $categoryRepository)
    {
        $this->middleware('jwt.auth')->only(["store", "update", "destroy"]);
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->all();
        return $this->sendResponse("Categories.", $categories);
    }

    public function store(Request $request)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('create', Category::class)) {
            $fields = $request->validate([
                "name" => ['required'],
            ]);

            $category = $this->categoryRepository->create($fields);
            return $this->sendResponse("category created.", $category, 201);
        } else {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $categoryExist = $this->categoryRepository->find($category->id);
        if (!$categoryExist) return $this->sendError("Category not found.");
        else return $this->sendResponse("Category.", $categoryExist);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('update', Category::class)) {
            $fields = $request->validate([
                "name" => ['required'],
            ]);

            $category = $this->categoryRepository->update($category->id, $fields);
            if (!$category) return $this->sendError("Something went wrong while updating");
            else return $this->sendResponse("category updated.", $category, 201);
        } else {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('delete', Category::class)) {
            $categoryDeleted = $this->categoryRepository->delete($category->id);
            if (!$categoryDeleted) return $this->sendError("Category not deleted.");
            else return response()->json(['message' => 'Category deleted'], 200);
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
