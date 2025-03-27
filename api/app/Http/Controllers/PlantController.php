<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use App\Models\User;
use App\Repositories\PlantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class PlantController extends Controller
{
    protected PlantRepository $plantRepository;

    public function __construct(PlantRepository $plantRepository)
    {
        $this->middleware('jwt.auth')->only(["store", "update", "destroy"]);
        $this->plantRepository = $plantRepository;
    }

    /**
     * @OA\Get(
     *     path="/plants",
     *     tags={"Plants"},
     *     summary="Get all plants",
     *     operationId="getPlants",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="All plants"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Plant")
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $plants = $this->plantRepository->all();
        return $this->sendResponse("All plants.", $plants);
    }

    /**
     * @OA\Post(
     *     path="/plants",
     *     tags={"Plants"},
     *     summary="Create a new plant",
     *     operationId="createPlant",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlantRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plant created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="plant", ref="#/components/schemas/Plant")
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
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 example={"name": {"The name field is required."}}
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('create', Plant::class)) {
            try {
                $fields = $request->validate([
                    "name" => ['required'],
                    "description" => ['required'],
                    "image" => ['required'],
                    "price" => ['required', 'numeric', 'min:10'],
                    "category_id" => ['required']
                ]);
            } catch (ValidationException $e) {
                return $this->sendError("Validation errors", $e->errors(), 422);
            }

            $plant = $this->plantRepository->create($fields);
            if (!$plant) return $this->sendError("Plant won't be created, check the category if it exist in your database.");
            else return response()->json(['plant' => $plant], 201);
        } else {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * @OA\Get(
     *     path="/plants/{slug}",
     *     tags={"Plants"},
     *     summary="Get specific plant by slug",
     *     operationId="getPlant",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plant not found")
     *         )
     *     )
     * )
     */
    public function show(Plant $plant)
    {
        return $this->plantRepository->find($plant->slug);
    }

    /**
     * @OA\Put(
     *     path="/plants/{id}",
     *     tags={"Plants"},
     *     summary="Update a plant",
     *     operationId="updatePlant",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlantRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plant updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="plant", ref="#/components/schemas/Plant")
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
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 example={"name": {"The name field is required."}}
     *             ),
     *             @OA\Property(property="status", type="integer", example=422)
     *         )
     *     )
     * )
     */
    public function update(Request $request, Plant $plant)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('update', Plant::class)) {
            try {
                $fields = $request->validate([
                    "name" => ['required'],
                    "description" => ['required'],
                    "image" => ['required'],
                    "price" => ['required', 'numeric', 'min:10'],
                    "category_id" => ['required']
                ]);
            } catch (ValidationException $e) {
                return $this->sendError("Validation errors", $e->errors(), 422);
            }

            $plant = $this->plantRepository->update($plant->id, $fields);
            if (!$plant) return $this->sendError("Plant won't be updated, check the category if it exist in your database.");
            else return response()->json(['plant' => $plant], 201);
        } else {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * @OA\Delete(
     *     path="/plants/{id}",
     *     tags={"Plants"},
     *     summary="Delete a plant",
     *     operationId="deletePlant",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plant deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Plant deleted successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Property(property="status", type="integer", example=200)
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
    public function destroy(Request $request, Plant $plant)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('delete', Category::class)) {
            $plantDeleted = $this->plantRepository->delete($plant->id);
            if (!$plantDeleted) return $this->sendError("Plant not deleted.");
            else return $this->sendResponse("Plant deleted succefully", []);
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
