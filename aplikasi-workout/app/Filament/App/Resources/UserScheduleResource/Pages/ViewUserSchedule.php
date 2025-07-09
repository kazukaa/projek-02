<?php

namespace App\Filament\App\Resources\UserScheduleResource\Pages;

use App\Filament\App\Resources\UserScheduleResource;
use Filament\Resources\Pages\ViewRecord; // Gunakan ViewRecord untuk halaman view
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class ViewUserSchedule extends ViewRecord
{
    protected static string $resource = UserScheduleResource::class;
    protected static ?string $navigationLabel = 'Detail Jadwal';
    protected static ?string $pluralModelLabel = 'Detail Jadwal';
    protected static ?string $navigationLabelPlural = 'Detail Jadwal';

    // Metode ini mendefinisikan apa yang akan ditampilkan
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('Nama Jadwal'),
                TextEntry::make('day_of_week')->label('Hari'),

                // ==> TAMBAHKAN BARIS INI <==
                TextEntry::make('exercises_list') // Beri nama yang unik
                    ->label('Daftar Latihan')
                    ->state(function ($record) {
                        // Ambil semua nama latihan yang berhubungan dan gabungkan dengan koma
                        return $record->exercises->pluck('name')->implode(', ');
                    })
                    ->columnSpanFull(), // Menggunakan columnSpanFull untuk mengisi lebar penuh

                IconEntry::make('is_rest_day')
                    ->label('Hari Istirahat')
                    ->boolean(),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
            ]);
    }
}
