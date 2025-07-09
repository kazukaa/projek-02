<x-filament-panels::page>
    @if (empty($achievements))
        {{-- Tampilkan pesan ini jika tidak ada pencapaian --}}
        <div class="flex items-center justify-center h-48 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
            <p class="text-gray-500 dark:text-gray-400">Anda belum mendapatkan lencana apa pun. Teruslah berlatih!</p>
        </div>
    @else
        {{-- Tampilkan lencana dalam bentuk grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($achievements as $achievement)
                <div class="p-6 bg-white dark:bg-gray-800/50 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 flex flex-col items-center text-center">
                    {{-- Asumsi data lencana memiliki 'image_url', 'name', dan 'description' --}}
                    
                    {{-- Gambar Lencana --}}
                    <img src="{{ $achievement['image_url'] ?? 'https://via.placeholder.com/100' }}" alt="{{ $achievement['name'] }}" class="w-24 h-24 mb-4 rounded-full object-cover">
                    
                    {{-- Nama Lencana --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $achievement['name'] }}
                    </h3>
                    
                    {{-- Deskripsi Lencana --}}
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $achievement['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>