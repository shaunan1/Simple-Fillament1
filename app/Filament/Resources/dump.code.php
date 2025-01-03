<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->options(Customer::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('payment_id')
                    ->label('Payment Method')
                    ->options(Payment::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Repeater::make('orderDetails')
                    ->relationship('orderDetail')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->options(Product::query()->where('stock', '>', 0)->pluck('name', 'id'))
                            ->required()
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                $product = Product::find($state);
                                $set('price', $product->price ?? 0);
                                $set('stock', $product->stock ?? 0);
                                self::updateTotalPrice($get, $set);
                            }),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                $stock = $get('stock');
                                if ($state > $stock) {
                                    $set('quantity', $stock);
                                    Notification::make()
                                        ->title('Warning')
                                        ->body('Stock is not enough')
                                        ->warning()
                                        ->send();
                                }
                                self::updateTotalPrice($get, $set);
                            }),

                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->required()
                            ->disabled(),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()
                            ->disabled(),
                    ]),

                Forms\Components\TextInput::make('total')
                    ->label('Total Price')
                    ->numeric()
                    ->disabled(),

                Forms\Components\Textarea::make('note')
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('payment.name')
                    ->label('Payment Method'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Price')
                    ->numeric(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function updateTotalPrice(Forms\Get $get, Forms\Set $set): void
    {
        $orderDetails = collect($get('orderDetails'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']));

        $total = $orderDetails->reduce(function ($carry, $item) {
            $product = Product::find($item['product_id']);
            return $carry + (($product->price ?? 0) * $item['quantity']);
        }, 0);

        $set('total', $total);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
