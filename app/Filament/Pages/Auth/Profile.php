<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Validation\Rules\Password;

class Profile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Authentication Details')
                    ->description('Your Login information')
                    ->schema([
                        // ...
                        TextInput::make('username')
                            ->required()
                            ->maxLength(255),
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),
                Section::make('API Authentication')
                    ->description('Manage your API Tokens')
                    ->schema([
                        // ...
                        TextInput::make('api_token')
                            ->label('API Token')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->hintAction(
                                Action::make('generateToken')
                                    ->label('Regenerate Token')
                                    ->icon('heroicon-m-arrow-path')
                                    // ->color('danger')
                                    ->requiresConfirmation()
                                    ->action(function (Set $set) {
                                        $token = auth()->user()->createToken('api_token');
                                        $set('api_token', $token->plainTextToken);
                                    })
                            )
                    ])
            ]);
    }
}
