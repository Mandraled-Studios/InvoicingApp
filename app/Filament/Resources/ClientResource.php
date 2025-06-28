<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Client;
use App\Enums\FirmType;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\ContactsRelationManager;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationGroup = 'Manage Clients';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('gstin')
                    ->label('GSTIN')
                    ->maxLength(15)
                    ->default(null),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(20)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('firm_type')
                    ->enum(FirmType::class)
                    ->options(FirmType::class),
                Forms\Components\TextInput::make('balance')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('paid_to_date')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('credit_balance')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('default_currency')
                    ->maxLength(4)
                    ->default('INR'),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->imageEditor()
                    ->default(null),
                Forms\Components\Fieldset::make('Address')
                    ->relationship('address')
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
                            ->maxLength(20),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gstin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('firm_type')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('address_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //AddressRelationManager::class,
            ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
