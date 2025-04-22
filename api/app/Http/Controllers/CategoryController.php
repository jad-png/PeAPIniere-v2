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

    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     operationId="getCategories",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Categories"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Category")
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = $this->categoryRepository->all();
        return $this->sendResponse("Categories.", $categories);
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     operationId="createCategory",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category created"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category"),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *         )
     *     )
     * )
     */
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
     * @OA\Get(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Get specific category",
     *     operationId="getCategory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(Category $category)
    {
        $categoryExist = $this->categoryRepository->find($category->id);
        if (!$categoryExist) return $this->sendError("Category not found.");
        else return $this->sendResponse("Category.", $categoryExist);
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     operationId="updateCategory",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category updated"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     operationId="deleteCategory",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
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
