<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Desarrollo Web',
            'Desarrollo Móvil',
            'Diseño Gráfico',
            'Marketing Digital',
            'Negocios y Emprendimiento',
            'Idiomas',
            'Música',
            'Fotografía y Video',
            'Salud y Fitness',
            'Cocina',
            'Desarrollo Personal',
            'Finanzas Personales',
            'Ciencia de Datos',
            'Inteligencia Artificial',
            'Ciberseguridad',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
