<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use Forms\Concerns\InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Profilim';
    protected static ?string $slug = 'profile';
    protected static string $view = 'filament.pages.profile';

    public $name;
    public $bio;
    public $profile_photo;
    public $cover_image;
    public $instagram;
    public $twitter;
    public $facebook;
    public $tiktok;
    public $soundcloud;
    public $password;
    public $email;

    public function mount()
    {
        $user = Auth::user();
        $this->form->fill([
            'name' => $user->name,
            'bio' => $user->bio,
            'profile_photo' => $user->profile_photo,
            'cover_image' => $user->cover_image,
            'instagram' => $user->instagram,
            'twitter' => $user->twitter,
            'facebook' => $user->facebook,
            'tiktok' => $user->tiktok,
            'soundcloud' => $user->soundcloud,
            'password' => '', // Password should not be pre-filled for security reasons
            'email' => $user->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('name')->label('Ad')->required(),
                TextInput::make('bio')->label('Biyografi')->nullable(),
                FileUpload::make('profile_photo')->label('Profil Fotoğrafı')->image()->directory('users'),
                FileUpload::make('cover_image')->label('Kapak Görseli')->image()->directory('users/covers'),
                TextInput::make('instagram')->nullable(),
                TextInput::make('twitter')->nullable(),
                TextInput::make('facebook')->nullable(),
                TextInput::make('tiktok')->nullable(),
                TextInput::make('soundcloud')->nullable(),
                TextInput::make('password')->password()->label('Şifre')->nullable()->dehydrated(false),
                TextInput::make('email')->email()->required(),
            ]),
        ];
    }

    public function save()
    {
        $user = Auth::user();
        $data = $this->form->getState();
        $user->update($data);
        Notification::make()
            ->title('Profiliniz güncellendi.')
            ->success()
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('dj');
    }
}
