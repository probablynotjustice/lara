<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('type')
                ->options([
                    'cat' => 'Cat',
                    'dog' => 'Dog',
                    'rabbit' => 'Rabbit',
                ])
                ->required(),


                Forms\Components\DatePicker::make('date_of_birth')
                ->required()
                ->maxDate(now()),


                Forms\Components\Select::make('owner_id')
                ->relationship('owner', 'name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->label('Phone number')
                        ->tel()
                        ->required(),
                ])
                ->required(),


                Forms\Components\TextInput::make('description')
                ->required()
                ->maxLength(255)
                ->columnSpan('full'),

                Forms\Components\Textarea::make('notes')
                ->maxLength(65535)
                ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TagsColumn::make('name')
                    ->searchable(),
                Tables\Columns\TagsColumn::make('type'),
                Tables\Columns\TagsColumn::make('date_of_birth'),
                Tables\Columns\TagsColumn::make('owner.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'cat' => 'cat',
                        'dog' => 'dog',
                        'rabbit' => 'rabbit',
                    ]),
                    Tables\Filters\SelectFilter::make('affilations')
                    ->options([
                        'cat' => 'cat',
                        'dog' => 'dog',
                        'rabbit' => 'rabbit',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
            RelationManagers\TreatmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
