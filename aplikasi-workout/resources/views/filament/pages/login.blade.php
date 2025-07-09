<x-filament-panels::page.simple>
    {{-- Tambahkan CSS untuk background --}}
    <style>
        body {
            /* Ganti dengan URL gambar pilihanmu */
            background-image: url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=2070');
            background-size: cover;
            background-position: center;
        }
        /* Membuat panel login sedikit transparan */
        .fi-simple-main {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>

    {{-- Ini adalah kode untuk menampilkan form login bawaan Filament --}}
    <x-filament-panels::form.actions 
        :actions="$this->getCachedFormActions()"
        :full-width="$this->hasFullWidthFormActions()"
    /> 

    {{ $this->form }}
</x-filament-panels::page.simple>