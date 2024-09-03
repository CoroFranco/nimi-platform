<x-appLayout>
    <main class="flex-grow p-8 md:p-12 lg:p-16 bg-gradient-to-br from-white to-gray-100">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-5xl font-bold mb-12 text-[var(--text-color)] text-center">Conviértete en Instructor</h1>

            <div class="bg-white rounded-2xl shadow-2xl p-12">
                <form action="{{ route('become.instructor') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="space-y-2">
                        <label for="bio" class="block text-2xl font-medium text-[var(--text-color)]">Biografía</label>
                        <textarea id="bio" name="bio" rows="6" class="instructorFormInput" placeholder="Cuéntanos sobre ti y tu experiencia...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <p class="mt-2 text-lg text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="expertise" class="block text-2xl font-medium text-[var(--text-color)]">Áreas de Experiencia</label>
                        <input type="text" id="expertise" name="expertise" value="{{ old('expertise') }}" class="instructorFormInput" placeholder="Ej: Programación, Diseño Gráfico, Marketing Digital">
                        @error('expertise')
                            <p class="mt-2 text-lg text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="teaching_experience" class="block text-2xl font-medium text-[var(--text-color)]">Experiencia en Enseñanza</label>
                        <select id="teaching_experience" name="teaching_experience" class="instructorFormInput">
                            <option value="">Selecciona una opción</option>
                            <option value="none" {{ old('teaching_experience') == 'none' ? 'selected' : '' }}>Sin experiencia previa</option>
                            <option value="some" {{ old('teaching_experience') == 'some' ? 'selected' : '' }}>Alguna experiencia</option>
                            <option value="experienced" {{ old('teaching_experience') == 'experienced' ? 'selected' : '' }}>Experimentado</option>
                            <option value="expert" {{ old('teaching_experience') == 'expert' ? 'selected' : '' }}>Experto</option>
                        </select>
                        @error('teaching_experience')
                            <p class="mt-2 text-lg text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="sample_video" class="block text-2xl font-medium text-[var(--text-color)]">Video de Muestra (opcional)</label>
                        <input type="text" id="sample_video" name="sample_video" value="{{ old('sample_video') }}" class="instructorFormInput" placeholder="URL de YouTube o Vimeo">
                        @error('sample_video')
                            <p class="mt-2 text-lg text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-2xl font-medium text-[var(--text-color)]">Redes Sociales (opcional)</label>
                        <div class="mt-2 space-y-4">
                            <input type="text" id="linkedin" name="social_media[linkedin]" value="{{ old('social_media.linkedin') }}" class="instructorFormInput" placeholder="LinkedIn URL">
                            <input type="text" id="twitter" name="social_media[twitter]" value="{{ old('social_media.twitter') }}" class="instructorFormInput" placeholder="Twitter URL">
                            <input type="text" id="website" name="social_media[website]" value="{{ old('social_media.website') }}" class="instructorFormInput" placeholder="Sitio Web Personal">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" class="h-6 w-6 text-[var(--hover-color)] focus:ring-[var(--hover-color)] border-gray-300 rounded">
                        <label for="terms" class="ml-3 block text-xl text-[var(--text-color)]">
                            Acepto los <a href="#" class="text-[var(--hover-color)] hover:underline">términos y condiciones</a> para instructores
                        </label>
                    </div>
                    @error('terms')
                        <p class="mt-2 text-lg text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-10">
                        <button type="submit" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-sm text-2xl font-medium text-white bg-[var(--hover-color)] hover:bg-[var(--hover-color-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--hover-color)] transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                            Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-appLayout>