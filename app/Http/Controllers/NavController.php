<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NavController extends Controller
{
    public function muestras()
    {
        $categories = Category::all();
        return view('create', compact('categories'));
    }

    public function home()
    {
        return view('home');
    }

    public function explorer()
    {
        $categories = Category::all();
        $courses = Course::all();
        return view('explorer', compact('categories', 'courses'));
    }

    public function myCourses()
    {
        $id = Auth::id(); // Obtener el ID del usuario autenticado

        // Obtener los cursos asociados al ID y cargar las lecciones asociadas
        $courses = Course::where('instructor_id', $id)
        ->with(['modules.lessons' => function ($query) {
            $query->orderBy('less_order'); // Ordenar y cargar la primera lección
        }])
        ->get();

        return view('myCourses', compact('courses'));
    }

    public function search(Request $request)
    {

        $query = Course::query();

        // Búsqueda por término
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "{$searchTerm}%");
            });
        }

        // Filtros por categorías
        if ($request->filled('categories')) {
            $categories = explode(',', $request->input('categories'));
            $query->whereIn('category_id', $categories);
        }

        // Filtros por niveles
        if ($request->filled('levels')) {
            $levels = explode(',', $request->input('levels'));
            $query->whereIn('level', $levels);
        }

        // Filtros por precio
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Ordenamiento
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('popularity', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }


        $courses = $query->with('category')->paginate(12);


        return response()->json([
            'courses' => $courses->items(),
            'pagination' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),

            ],
        ]);
    }
}
