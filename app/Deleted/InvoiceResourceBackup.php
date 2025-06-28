<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InvoiceResource\RelationManagers;

class InvoiceResourceBackup extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        $isCreate = $form->getOperation() === "create";
        $isEdit = $form->getOperation() === "edit";
        $clientInfo = '';
        $clientState = null;
        $tax1_label = null;
        $tax2_label = null;
        $tax1 = 0;
        $tax2 = 0;
        $total = 0;


        $arr = [
           'contacts' => 'blank',
           'address' => 'blank2',
        ];

        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if($clientState == 'Tamilnadu' || $clientState == 'Tamil nadu' || $clientState == 'Tamil Nadu') {
                            $tax1_label = 'CGST';
                            $tax2_label = 'SGST';
                            $tax1 = $task->billing_value ? (float)$task->billing_value * 0.09 : 0;
                            $tax2 = $task->billing_value ? (float)$task->billing_value * 0.09 : 0;
                        } else {
                            $tax1_label = 'IGST';
                            $tax2_label = null;
                            $tax1 = $task->billing_value ? (float)$task->billing_value * 0.18 : 0;
                            $tax2 = 0;
                        }
                        $arr = [
                            'contacts' => $get('client_id'),
                            'address' => $get('client_id'),
                        ];
                    })
                    ->required(),
                /*Forms\Components\ViewField::make('client_details')
                    ->view('filament.forms.components.client_details')
                    ->viewData($arr),
                    */
                Forms\Components\TextInput::make('invoice_number')
                    ->required()
                    ->maxLength(64),
                Forms\Components\DateTimePicker::make('invoice_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('invoice_duedate'),
                Forms\Components\Toggle::make('taxes_for_line_item')
                    ->required(),
                Forms\Components\TextInput::make('invoice_subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                Forms\Components\TextInput::make('tax1_label')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('tax1_value')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                Forms\Components\TextInput::make('tax2_label')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('tax2_value')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                Forms\Components\TextInput::make('round_off')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                Forms\Components\TextInput::make('discount_value')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('discount_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('invoice_total')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                Forms\Components\TextInput::make('paid_to_date')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                Forms\Components\TextInput::make('balance_due')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_duedate')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('taxes_for_line_item')
                    ->boolean(),
                Tables\Columns\TextColumn::make('invoice_subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax1_label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax1_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax2_label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax2_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('round_off')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_to_date')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance_due')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.id')
                    ->numeric()
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
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
