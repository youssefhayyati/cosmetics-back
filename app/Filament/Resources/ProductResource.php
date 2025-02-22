<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('1.5'),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpan('1.5'),
                Forms\Components\TextInput::make('price')
                    ->prefix('DH')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('collection')
                    ->required()
                    ->relationship('collections', 'name')
                    ->preload(true)
                    ->multiple()
                    ->searchable(),
                Forms\Components\Select::make('size')
                    ->required()
                    ->relationship('sizes', 'name')
                    ->preload(true)
                    ->multiple()
                    ->searchable(),
                // Forms\Components\Select::make('colors')
                //     ->required()
                //     ->preload(true)
                //     ->multiple()
                //     ->searchable()
                //     ->relationship('colors', 'name')
                //     ->options(
                //         \App\Models\Color::all()->mapWithKeys(function ($color) {
                //             return [
                //                 $color->id => '<div style="display: flex; align-items: center; gap: 10px;">
                //                                     <span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; background-color: ' . $color->hex_code . ';"></span>
                //                                     <span>' . $color->name . '</span>
                //                                 </div>',
                //             ];
                //         })->toArray()
                //     )
                //     ->allowHtml(),
                Forms\Components\FileUpload::make('images')
                    ->directory('products')
                    ->image()
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->required()
                    ->multiple()
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorMode(1)
                    ->downloadable()
                    ->panelLayout('grid')
                    ->openable()
                    ->maxSize(30024)
                    ->columnSpanFull(),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('description')
                    ->wrap(),
                Tables\Columns\ImageColumn::make('images')
                    ->label('Images')
                    ->circular()
                    ->size(50)
                    ->alignCenter()
                    ->stacked()
                    // ->wrap()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->disk('public'),
                // Tables\Columns\ColorColumn::make('colors')
                //     ->getStateUsing(fn($record) => $record->colors->pluck('hex_code')->toArray())
                //     ->label('Colors')
                //     ->alignCenter()
                //     ->wrap()
                //     ->copyable()
                //     ->copyMessage('Color code copied')
                //     ->toggleable()
                //     ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('price')
                    ->suffix(' DH')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
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

    public static function getRelations(): array
    {
        return [
            // RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
