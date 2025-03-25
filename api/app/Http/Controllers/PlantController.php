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

    public function index()
    {
        $plants = $this->plantRepository->all();
        return $this->sendResponse("All plants.", $plants);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Plant $plant)
    {
        return $this->plantRepository->find($plant->slug);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
