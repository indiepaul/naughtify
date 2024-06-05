<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Notification;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class Notifications extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(Notification::latest())
            ->columns([
                TextColumn::make('message')->grow(),
                TextColumn::make('created_at')
                    ->label('Time')
                    ->since(),
            ]);
    }
}
