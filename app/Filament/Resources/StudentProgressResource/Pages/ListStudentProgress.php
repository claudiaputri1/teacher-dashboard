<?php

namespace App\Filament\Resources\StudentProgressResource\Pages;

use App\Filament\Resources\StudentProgressResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentProgress extends ListRecords
{
    protected static string $resource = StudentProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
