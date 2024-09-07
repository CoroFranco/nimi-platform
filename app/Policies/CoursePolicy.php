<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Por ejemplo, permitir que cualquier usuario autenticado vea cualquier curso
        return $user->isAuthenticated(); // Cambia esto según tu lógica
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        // Permitir que el usuario vea el curso si es el instructor o si es un usuario autenticado
        return $user->id === $course->instructor_id || $user->isAuthenticated();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Permitir la creación de cursos solo a usuarios autenticados
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        // Permitir la actualización del curso solo al instructor del curso
        return $user->id === $course->instructor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        // Permitir la eliminación del curso solo al instructor del curso
        return $user->id === $course->instructor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        // Permitir la restauración del curso solo al instructor del curso
        return $user->id === $course->instructor_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        // Permitir la eliminación permanente del curso solo al instructor del curso
        return $user->id === $course->instructor_id;
    }
}
