<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Address;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AddressResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AddressResource\RelationManagers;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationGroup = 'Location';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('building_number')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('street_address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->label('Area / Location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('country_id')
                    ->options(Country::all()->pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->required(),
                Forms\Components\Select::make('state_id')
                    ->searchable()
                    ->relationship('state', 'name', function(Builder $query, Forms\Get $get){
                        return $query->where('country_id', $get('country_id'));
                    })
                    ->live()
                    ->required(),
                Forms\Components\Select::make('city_id')
                    ->searchable()
                    ->relationship('city', 'name', function(Builder $query, Forms\Get $get){
                        return $query->where('state_id', $get('state_id'));
                    })
                    ->required(),
                Forms\Components\TextInput::make('zipcode')
                    ->required()
                    ->maxLength(6),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('building_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('street_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('City')
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('State')
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country')
                    ->sortable(),
                Tables\Columns\TextColumn::make('zipcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ManageAddresses::route('/'),
        ];
    }
}
