<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $navigationGroup = 'Manajemen Aplikasi';

    public static function form(Form $form): Form
    {
        // Ini adalah formulir untuk "Create User" dan "Edit User"
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), // Unik, tapi abaikan untuk user yg sedang diedit
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Tanggal Verifikasi Email'),
                // Hanya minta password saat membuat user baru
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Ini adalah tabel daftar pengguna yang akan kita lihat
        return $table
            ->columns([
                // Kolom untuk Nama & Email dengan fitur pencarian
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                
                // Kolom untuk melihat status verifikasi
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Terverifikasi')
                    ->boolean(),

                // Kolom untuk melihat TINGKAT AKTIVITAS pengguna
                Tables\Columns\TextColumn::make('workout_logs_count')
                    ->counts('workoutLogs') // Menghitung dari relasi
                    ->label('Aktivitas')
                    ->sortable(),

                // Kolom untuk melihat kapan pengguna bergabung
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Bergabung')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Bisa disembunyikan
            ])
            ->filters([
                // Filter akan kita tambahkan nanti
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
