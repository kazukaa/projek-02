<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Infolists; // <-- TAMBAHKAN INI
use Filament\Infolists\Infolist; // <-- TAMBAHKAN INI
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $navigationLabelPlural = 'Kategori';
    protected static ?int $navigationSort = 1; // Urutan di menu navigasi
    protected static ?string $navigationGroup = 'Konten';
    // Method ini mendefinisikan apa yang akan ditampilkan di halaman "View"
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Bagian untuk info utama
                Infolists\Components\Section::make('Informasi Kategori')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')->label('Nama Kategori'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(1),
                
                // Bagian untuk menampilkan daftar latihan di dalamnya
                Infolists\Components\Section::make('Latihan dalam Kategori Ini')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('exercises')
                            ->label('') // Kosongkan label agar tidak ada judul tambahan
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama Latihan')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('difficulty')
                                    ->label('Tingkat Kesulitan')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('duration_seconds')
                                    ->label('Durasi')
                                    ->suffix(' detik'),
                            ])->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('exercises_count')
                    ->counts('exercises')
                    ->label('Jumlah Latihan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'view' => Pages\ViewCategory::route('/{record}'),
        ];
    }    
}
