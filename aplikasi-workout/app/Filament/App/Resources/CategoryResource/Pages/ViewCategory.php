<?php

namespace App\Filament\App\Resources\CategoryResource\Pages;

use App\Filament\App\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    // PERBAIKAN: Override method ini untuk menghilangkan semua tombol di header
    protected function getHeaderActions(): array
    {
        // Kembalikan array kosong agar tidak ada tombol yang muncul
        return [];
    }
}
