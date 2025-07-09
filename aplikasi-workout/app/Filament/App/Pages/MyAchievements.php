<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
// Hapus "use Illuminate\Support\Facades\Http;" karena tidak lagi digunakan

class MyAchievements extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static string $view = 'filament.app.pages.my-achievements';
    protected static ?string $title = 'Pencapaian Saya';
    protected static ?string $navigationLabel = 'Pencapaian Saya';
    protected static ?string $navigationGroup = 'Akun Saya';

    public array $achievements = [];

    // Metode mount() yang sudah diperbaiki
    public function mount(): void
{
    $user = Auth::user();

    if ($user) {
        /** @var \App\Models\User $user */ // <-- TAMBAHKAN BARIS INI
        $this->achievements = $user->achievements()->get()->toArray();
    } else {
        $this->achievements = [];
    }
}
}