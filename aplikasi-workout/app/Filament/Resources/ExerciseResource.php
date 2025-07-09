<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseResource\Pages;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;

    /**
     * Mengatur properti navigasi di sidebar.
     */
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationLabel = 'Semua Latihan';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $pluralModelLabel = 'Semua Latihan';
    protected static ?string $navigationLabelPlural = 'Semua Latihan'; // Label untuk daftar latihan
    protected static ?int $navigationSort = 2; // Menentukan urutan di sidebar

    /**
     * Menampilkan jumlah total latihan sebagai badge notifikasi di sidebar.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    /**
     * Mendefinisikan skema form untuk membuat dan mengedit data.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name') // 'category' adalah nama relasi, 'name' adalah kolom yang ditampilkan
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Kategori'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                // Menggunakan RichEditor untuk deskripsi yang lebih detail
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('video_url')
                    ->label('URL Video Tutorial')
                    ->url()
                    ->maxLength(255),

                Forms\Components\Select::make('difficulty') // Menggunakan 'level' sesuai dengan tabel workout plan
                    ->label('Tingkat Kesulitan')
                    ->options([
                        'pemula' => 'Pemula',
                        'menengah' => 'Menengah',
                        'lanjut' => 'Lanjut',
                        'semua' => 'Semua Level', // Tambahkan opsi untuk semua level
                    ])
                    ->required(),
                Forms\Components\TextInput::make('duration_seconds')
                    ->label('Durasi (Detik)')
                    ->numeric()
                    ->required()
                    ->default(30) // Nilai default 30 detik
                    ->minValue(1) // Minimal 1 detik
            ]);
    }

    /**
     * Mendefinisikan kolom-kolom yang akan ditampilkan pada tabel.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Latihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge() // Menampilkan sebagai badge
                    ->searchable()
                    ->sortable(),

                // PERBAIKAN & PENYEMPURNAAN KOLOM TINGKAT KESULITAN
                Tables\Columns\TextColumn::make('difficulty')
                    ->label('Tingkat Kesulitan')
                    ->badge() // Tampilkan juga sebagai badge
                    ->color(fn(string $state): string => match ($state) {
                        'pemula' => 'success', // Hijau untuk pemula
                        'menengah' => 'warning', // Kuning untuk menengah
                        'lanjut' => 'danger', // Merah untuk lanjut
                        default => 'gray', // Abu-abu untuk lainnya
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('Durasi (detik)')
                    ->sortable()
                    ->numeric()
                    ->suffix(' detik'), // Menambahkan satuan detik 
            ])
            ->filters([
                // MENAMBAHKAN FILTER INTERAKTIF
                SelectFilter::make('difficulty')
                    ->options([
                        'pemula' => 'Pemula',
                        'menengah' => 'Menengah',
                        'lanjut' => 'Lanjut',
                        'semua' => 'Semua Level', // Tambahkan opsi untuk semua level
                    ])
                    ->label('Filter Berdasarkan Kesulitan'),
            ])
            ->actions([
                // MENGUBAH TOMBOL MENJADI IKON
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relation Managers bisa didaftarkan di sini jika ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExercises::route('/'),
            'create' => Pages\CreateExercise::route('/create'),
            'edit' => Pages\EditExercise::route('/{record}/edit'),
        ];
    }
}
