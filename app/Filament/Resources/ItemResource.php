<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'fal-egg';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('quantity'),
                TextInput::make('cost')
                    ->prefix('£'),
                Select::make('store')
                    ->searchable()
                    ->options([
                        'morrisons' => 'Morrison\'s',
                        'food-warehouse' => 'Food Warehouse',
                        'pets-at-home' => 'Pets at Home',
                        'sainsburys' => 'Sainsburys',
                    ])
                    ->default('morrisons')
                    ->required(),
                TextInput::make('aisle')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('store')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'morrisons' => 'yellow',
                        'food-warehouse' => 'red',
                        'pets-at-home' => 'green',
                        'sainsburys' => 'orange',
                    })
                    ->formatStateUsing(fn (Model $record) => Str::headline($record->store)),
                TextColumn::make('aisle'),
                TextColumn::make('cost')->prefix('£')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageItems::route('/'),
        ];
    }    
}
