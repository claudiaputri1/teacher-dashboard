<?php

namespace App\Filament\Resources\Module;

use App\Models\Module;
use Filament\Resources\Resource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Modules';
}
