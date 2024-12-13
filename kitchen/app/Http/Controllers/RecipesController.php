<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Services\Recipes\GetAllRecipes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class RecipesController
{
    //
    public function index(GetAllRecipes $action): JsonResponse
    {
        return Response::JSON(RecipeResource::collection($action->handle()));
    }
}
