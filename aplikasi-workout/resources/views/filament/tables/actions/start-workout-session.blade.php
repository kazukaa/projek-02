<div>
    <h3 class="text-lg font-medium leading-6 text-gray-950 dark:text-white mb-4">
        Rangkaian Latihan
    </h3>

    <ul role="list" class="divide-y divide-gray-200 dark:divide-white/10">
        @forelse ($exercises as $exercise)
            <li class="flex items-center justify-between gap-x-6 py-3">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $exercise->name }}
                    </p>
                    <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">
                        Durasi: {{ $exercise->pivot->duration_seconds ?? $exercise->duration_seconds }} detik
                    </p>
                </div>

                <div class="flex flex-none items-center gap-x-4">
                    <button
                        x-on:click="$dispatch('speak-text', { text: '{{ addslashes($exercise->name) }}' })"
                        type="button"
                        title="Dengarkan Panduan Suara"
                        class="text-xs font-semibold text-green-600 hover:text-green-500"
                    >
                        Suara
                    </button>
                    <button
                        x-on:click="$dispatch('start-timer', { duration: {{ $exercise->pivot->duration_seconds ?? $exercise->duration_seconds }}, exerciseId: {{ $exercise->id }} })"
                        type="button"
                        title="Mulai Timer"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-500"
                    >
                        Timer
                    </button>
                </div>
            </li>
        @empty
            <li>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada latihan dalam jadwal ini.</p>
            </li>
        @endforelse
    </ul>
</div>