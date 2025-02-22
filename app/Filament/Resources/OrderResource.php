<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('client_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_phone')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'pending',
                        'completed' => 'completed',
                        'cancelled' => 'cancelled',
                    ])
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('client_email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('client_phone')
                    ->label('Phone')
                    ->searchable()
                    ->icon('heroicon-m-phone'),

                // Products Section
                Tables\Columns\TextColumn::make('products_list')
                    ->label('Products')
                    ->getStateUsing(function ($record) {
                        return $record->products->pluck('title')->join(', ');
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('product_sizes')
                    ->label('Sizes')
                    ->getStateUsing(function ($record) {
                        return $record->products->pluck('pivot.size')->join(', ');
                    }),

                // Tables\Columns\ColorColumn::make('product_colors')
                //     ->label('Colors')
                //     ->getStateUsing(function ($record) {
                //         return $record->products->pluck('pivot.color');
                //     }),

                Tables\Columns\TextColumn::make('product_quantities')
                    ->label('Quantities')
                    ->getStateUsing(function ($record) {
                        return $record->products->pluck('pivot.quantity')->join(', ');
                    })
                    ->alignment('center'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Total')
                    ->getStateUsing(function ($record) {
                        $total = $record->products->sum(function ($product) {
                            return $product->pivot->quantity * $product->price;
                        });
                        return number_format($total, 2) . ' DH';
                    })
                    ->alignment('left')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'success' => 'completed',
                    ])
                    ->label('Status')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
