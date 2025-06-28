<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use App\Models\Client;
use App\Enums\FirmType;
use App\Enums\TaxLabel;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\InvoiceStatus;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use App\Forms\Components\ClientInfo;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Filament\Resources\InvoiceResource\Widgets\TopClientStat;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceBasicStats;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    //public Invoice $invoice;

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
        $subtotal = 0;
        $total = 0;

        //Autofill Default Invoice Number
        $lastInvoice = Invoice::latest()->first();
        $lastInvoiceID = $lastInvoice ? $lastInvoice->id : 0;
        $prefix = 'MSIN' . date('Ym') . '/';
        $nextInvoiceId = (int) $lastInvoiceID + 1;
        $invoiceNumber = $prefix . str_pad($nextInvoiceId, 5, "0", STR_PAD_LEFT);


        $arr = [
            'contacts' => 'blank',
            'address' => 'blank2',
        ];

        return $form
            ->schema([
                Section::make('Invoice For / Client Details')
                    ->schema([
                        Section::make()
                            ->schema([
                                Forms\Components\Select::make('client_id')
                                    ->relationship('client', 'company_name')
                                    ->searchable()
                                    ->createOptionForm([
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
                                                    ->relationship('state', 'name', function (Builder $query, Forms\Get $get) {
                                                        return $query->where('country_id', $get('country_id'));
                                                    })
                                                    ->live()
                                                    ->required(),
                                                Forms\Components\Select::make('city_id')
                                                    ->searchable()
                                                    ->relationship('city', 'name', function (Builder $query, Forms\Get $get) {
                                                        return $query->where('state_id', $get('state_id'));
                                                    })
                                                    ->required(),
                                                Forms\Components\TextInput::make('zipcode')
                                                    ->required()
                                                    ->maxLength(20),
                                        ]),
                                    ])->columns(2)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $client = Client::find($get('client_id'));
                                        $clientAddress = $client ? $client->address : '';
                                        $clientContact = $client->contacts()->first();
                                        $city = City::find($clientAddress->city_id);
                                        $state = State::find($clientAddress->state_id);
                                        $country = Country::find($clientAddress->country_id);

                                        $contact = $clientContact->firstname . ' ' . $clientContact->lastname;
                                        $address = $clientAddress->building_number . ', ' . $clientAddress->street_address . ', ' . $clientAddress->location . ', ' . $city->name . ', ' . $state->name . ', ' . $country->name;
                                        $set('client_address', $address);
                                        $set('gstin', $client->gstin);
                                        $set('primary_contact', $contact);
                                        $set('website', $client->website);
                                        $set('company_email', $client->email);
                                        $set('contact_email', $clientContact->email);
                                        $set('contact_phone', $clientContact->phone);

                                    }),
                                Forms\Components\TextInput::make('gstin')
                                    ->label('GSTIN')
                                    ->default(''),
                                Forms\Components\TextInput::make('company_email')
                                    ->label('Company Email')
                                    ->default(''),
                            ])->columnSpan(2),
                        Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('website')
                                    ->default(''),
                                Forms\Components\TextArea::make('client_address')
                                    ->default('')
                                    ->rows(5)
                                    ->columnSpan(2),
                            ])->columnSpan(2),
                        Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('primary_contact')
                                    ->default(''),
                                Forms\Components\TextInput::make('contact_email')
                                    ->default(''),
                                Forms\Components\TextInput::make('contact_phone')
                                    ->default(''),
                            ])->columnSpan(2),
                    ])->visible($isCreate)->columns(6),
                Section::make('Invoice Details')
                    ->schema([
                        Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('invoice_number')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->default($invoiceNumber)
                                    ->maxLength(20),
                                Forms\Components\Select::make('invoice_status')
                                    ->enum(InvoiceStatus::class)
                                    ->options(InvoiceStatus::class)
                                    ->default('Generated'),
                            ])->columnSpan(1),
                        Section::make()
                            ->schema([
                                Forms\Components\DatePicker::make('invoice_date')
                                    ->default(today())
                                    ->required(),
                                Forms\Components\DatePicker::make('invoice_duedate')
                                    ->default(now()->addDays(15))
                                    ->required(),
                            ])->columnSpan(1),
                        Section::make()
                            ->schema([
                                Forms\Components\Select::make('discount_type')
                                    ->options([
                                        "none" => "None",
                                        "amount" => "By Amount",
                                        "percentage" => "By Percentage",
                                    ])
                                    ->default('none'),
                                Forms\Components\TextInput::make('discount_value')
                                    ->numeric()
                                    ->default(0.00),
                            ])->columnSpan(1),
                    ])->columns(3),
                Section::make('Invoice Items')
                    ->schema([
                        Repeater::make('invoiceProducts')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Forms\Components\RichEditor::make('description')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->default(0.00)
                                            ->prefix('₹'),
                                        Forms\Components\TextInput::make('hsncode')
                                            ->label('HSN/SAC Code')
                                            ->numeric(),
                                        Forms\Components\Select::make('collection_id')
                                            ->relationship('collection', 'name')
                                            ->required(),
                                    ])
                                    //->options(Product::query()->pluck('name', 'id'))
                                    ->reactive()
                                    ->required()
                                    ->searchable()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('description', $product->description);
                                            $set('hsncode', $product->hsncode);
                                            $set('price_override', $product->price);
                                        }
                                    })
                                    ->columnSpan(2),
                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('hsncode')
                                    ->label('HSN / SAC Code')
                                    ->numeric()
                                    ->default(998315)
                                    ->minValue(000000)
                                    ->maxValue(999999),
                                Forms\Components\TextInput::make('price_override')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->default(0),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('Add Line Item')
                            ->columns(6)
                            ->cloneable(),
                    ]),
                Section::make('Taxes')
                    ->schema([
                        Forms\Components\Select::make('tax1_label')
                            ->enum(TaxLabel::class)
                            ->options(TaxLabel::class)
                            ->default($tax1_label)
                        ,
                        Forms\Components\TextInput::make('tax1_value')
                            ->numeric()
                            ->prefix('₹')
                            ->default($tax1 ?? 0)
                        ,
                        Forms\Components\Select::make('tax2_label')
                            ->enum(TaxLabel::class)
                            ->options(TaxLabel::class)
                            ->default($tax2_label)
                        ,
                        Forms\Components\TextInput::make('tax2_value')
                            ->numeric()
                            ->prefix('₹')
                            ->default($tax2 ?? 0)
                        ,
                    ])->aside()->columns(2),
                Section::make('Total Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_subtotal')
                            ->required()
                            ->numeric()
                            ->prefix('₹')
                            ->default($subtotal),
                        Forms\Components\TextInput::make('round_off')
                            ->numeric()
                            ->prefix('₹')
                            ->minValue(-0.99)
                            ->maxValue(0.99)
                            ->step(0.01)
                            ->default(0.00)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                $tot = (float) $get('subtotal') + (float) $get('tax1') + (float) $get('tax2') + $state;
                                $set('total', $tot);
                            })
                        ,
                        Forms\Components\TextInput::make('invoice_total')
                            ->required()
                            ->numeric()
                            ->prefix('₹')
                            ->default($total)
                        ,
                    ])->aside(),
            ]);
        // ->statePath('data')
        // ->model(Invoice::class);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.company_name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_duedate')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('taxes_for_line_item')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('invoice_subtotal')
                    ->numeric()
                    ->money('INR'),
                Tables\Columns\TextColumn::make('tax1_label')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tax1_value')
                    ->numeric()
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax2_label')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tax2_value')
                    ->numeric()
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('round_off')
                    ->numeric()
                    ->money('INR')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount_value')
                    ->numeric()
                    ->money('INR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount_type')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('invoice_total')
                    ->numeric()
                    ->money('INR')
                    ->sortable()
                    // ->size(TextColumn\TextColumnSize::Large)
                    ->badge(),
                Tables\Columns\TextColumn::make('paid_to_date')
                    ->numeric()
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance_due')
                    ->numeric()
                    ->money('INR')
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('financial_year')
                    ->form([
                        Forms\Components\Select::make('financial_year')
                            ->options([
                                'all' => 'All',
                                '2026' => '2025-26',
                                '2025' => '2024-25',
                                '2024' => '2023-24',
                                '2023' => '2022-23',
                                '2022' => '2021-22',
                                '2021' => '2020-21',
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['financial_year'] == 'all') {
                            $date1 = Date('Y-m-d', strtotime('1 Apr 1990'));
                            $date2 = Date('Y-m-d', strtotime('31 March 2100'));
                        } else {
                            $startYear = intval($data['financial_year']) - 1;
                            $date1 = Date('Y-m-d', strtotime('1 April ' . $startYear));
                            $date2 = Date('Y-m-d', strtotime('31 March ' . $data['financial_year']));
                        }

                        $dateRange = [$date1, $date2];

                        return $query
                            ->whereNull('deleted_at')
                            ->when(
                                $dateRange,
                                fn(Builder $query, $dateRange): Builder => $query->whereBetween('invoice_date', $dateRange),
                            );
                    })
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
            ])
            ->defaultPaginationPageOption(50);
    }

    protected static function getTableDefaultSort(): array
    {
        return ['created_at' => 'desc']; // Sorts by created_at in descending order
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

    public static function getWidgets(): array
    {
        return [
            InvoiceBasicStats::class,
            TopClientStat::class,
        ];
    }
}
