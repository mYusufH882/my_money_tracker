<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);

        return $this->successWithPagination(CategoryResource::collection($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return $this->createdResponse(CategoryResource::make($category), 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        return $this->successResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        $category->update($request->validated());

        return $this->updatedResponse(new CategoryResource($category), 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->notFoundResponse('Category not found');
        }

        $category->delete();

        return $this->deletedResponse('Category deleted successfully');
    }
}
