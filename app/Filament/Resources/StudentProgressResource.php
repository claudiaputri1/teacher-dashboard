<?php

namespace App\Filament\Resources;

use App\Models\StudentProgress;
use Filament\Resources\Resource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class StudentProgressResource extends Resource
{
    protected static ?string $model = StudentProgress::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Student Progress';
}
