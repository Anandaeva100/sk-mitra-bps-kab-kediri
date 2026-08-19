<x-filament-panels::page.simple>
    <div class="flex flex-col items-center mb-2 text-center">

        <!-- Judul Form Register -->
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white -mt-2">
            Daftar Akun
        </h1>

        <!-- Teks Tautan Masuk -->
        @if (filament()->hasLogin())
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                atau 
                <a 
                    href="{{ filament()->getLoginUrl() }}" 
                    class="font-bold underline"
                    style="color: #d97706;"
                >
                    masuk ke akun Anda
                </a>
            </p>
        @endif
    </div>

    <!-- Form Register Filament -->
    <x-filament-panels::form wire:submit="register">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
</x-filament-panels::page.simple>