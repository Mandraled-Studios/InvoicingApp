<?php 

namespace App\Filament\Pages\Settings;
 
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
 
class Settings extends BaseSettings
{
    public static function getNavigationLabel(): string
    {
        return 'App Settings';
    }

    public function getTitle(): string
    {
        return 'App Settings';
    }

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->schema([
                            TextInput::make('general.brand_name')
                                ->required(),
                            FileUpload::make('general.logo')
                                ->image()
                                ->imageEditor()
                                ->default(null),
                        ]),
                    Tabs\Tab::make('Seo')
                        ->schema([
                            TextInput::make('seo.title')
                                ->required(),
                            TextInput::make('seo.description')
                                ->required(),
                        ]),
                ]),
        ];
    }
}