<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Filament\Resources\PatientResource\RelationManagers\TreatmentsRelationManager;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                TextInput::make('name')->required()->maxLength(255)->autocapitalize('words'),
                TextInput::make('backgroundColor')
                    ->type('color'),
                TextInput::make('number')
                    ->numeric()
                    ->step(100),
                TextInput::make('cardNumber')
                    ->mask(RawJs::make(<<<'JS'
        $input.startsWith('34') || $input.startsWith('37') ? '9999 999999 99999' : '9999 9999 9999 9999'
    JS)),
                TextInput::make('cost')
                    ->prefix('€')
                    ->suffixAction(
                        Action::make('copyCostToPrice')
                            ->icon('heroicon-m-clipboard')
                            ->requiresConfirmation()
                            ->action(function (Set $set, $state) {
                                $set('price', $state);
                            })
                    ),
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Tab 1')
                            ->schema([
                                TextInput::make('name')->required()->maxLength(255)->autocapitalize('words'),
                            ]),
                        Tabs\Tab::make('Tab 2')
                            ->schema([
                                TextInput::make('father_name')->required()->maxLength(255)->autocapitalize('words'),
                            ]),
                        Tabs\Tab::make('Tab 3')
                            ->schema([
                                TextInput::make('phone')->required()->maxLength(255)->autocapitalize('words'),
                            ]),
                    ]),
                Wizard::make([
                    Wizard\Step::make('Order')
                        ->schema([
                            // ...
                        ]),
                    Wizard\Step::make('Delivery')
                        ->schema([
                            // ...
                        ]),
                    Wizard\Step::make('Billing')
                        ->schema([
                            // ...
                        ]),
                ]),
                TextInput::make('password')
                    ->password()
                    ->autocomplete('new-password')->revealable(),
                TextInput::make('birthday')
                    ->mask('99/99/9999')
                    ->placeholder('MM/DD/YYYY'),
                TextInput::make('manufacturer')
                    ->datalist([
                        'BWM',
                        'Ford',
                        'Mercedes-Benz',
                        'Porsche',
                        'Toyota',
                        'Tesla',
                        'Volkswagen',
                    ]),
                TextInput::make('domain')
                    ->url()
                    ->suffixIcon('heroicon-m-check-circle')
                    ->suffixIconColor('success'),
                TextInput::make('domain')
                    ->prefix('https://')
                    ->suffix('.com'),
                Select::make('type')->options([
                    'cat' => 'Cat',
                    'dog' => 'Dog',
                    'rabbit' => 'Rabbit',
                    'parrot' => 'Parrot',
                    'hamster' => 'Hamster',
                    'bird' => 'Bird',
                ])->required(),
                DatePicker::make('date_of_birth')->required()->maxDate(now()),
                Select::make('owner_id')->required()->relationship('owner', 'name')->searchable()->preload()
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->searchable(),
                TextColumn::make('date_of_birth')->sortable(),
                TextColumn::make('owner.name')->searchable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'cat' => 'Cat',
                        'dog' => 'Dog',
                        'rabbit' => 'Rabbit',
                        'parrot' => 'Parrot',
                        'hamster' => 'Hamster',
                        'bird' => 'Bird',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            TreatmentsRelationManager::class,
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
