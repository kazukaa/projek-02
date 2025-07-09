<?php

namespace App\Filament\App\Resources\UserScheduleResource\Pages;

use App\Filament\App\Resources\UserScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserSchedules extends ListRecords
{
    protected static string $resource = UserScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
