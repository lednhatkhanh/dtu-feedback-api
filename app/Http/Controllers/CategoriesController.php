<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoriesController extends BaseController
{
    function index() {
        $categories = Category::paginate(10);
        return $this->response->paginator($categories, new CategoryTransformer);
    }
}
