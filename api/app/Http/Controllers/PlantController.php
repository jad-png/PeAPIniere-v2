<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse("All plants.", Plant::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize("create");
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

        $category = Category::find($fields["category_id"]);
        if (!$category) {
            return $this->sendError("Category ID " . $fields["category_id"] . " does not exists", [], 404);
        }

        $plant = Plant::create($fields);
        return response()->json(['plant' => $plant], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plant $plant)
    {
        return response()->json(['Plant' => $plant], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        $this->authorize("update");
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

        $category = Category::find($fields["category_id"]);
        if (!$category) {
            return $this->sendError("Category ID " . $fields["category_id"] . " does not exists", [], 404);
        }

        $plant->update($fields);
        return $this->sendResponse("Plant updated succefully", $plant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant)
    {
        $this->authorize("delete");
        $plant->delete();
        return $this->sendResponse("Plant deleted succefully", []);
    }
}
