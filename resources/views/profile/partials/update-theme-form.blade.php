<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Apparence') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Choisissez le thème visuel de votre interface.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.theme') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="theme" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thème</label>
            <select name="theme" id="theme" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="light" {{ auth()->user()->theme === 'light' ? 'selected' : '' }}>☀️ Clair</option>
                <option value="dark" {{ auth()->user()->theme === 'dark' ? 'selected' : '' }}>🌙 Sombre</option>
            </select>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Appliquer') }}
            </button>

            @if (session('status') === 'theme-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Thème mis à jour.') }}
                </p>
            @endif
        </div>
    </form>
</section>
