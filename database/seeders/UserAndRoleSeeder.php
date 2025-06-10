<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Kendi User modelinizin namespace'ini doğru yazdığınızdan emin olun
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Admin ve Dj rollerini oluştur
        // Eğer roller zaten varsa tekrar oluşturmaz.
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $djRole = Role::firstOrCreate(['name' => 'dj']);

        $this->command->info('Admin ve Dj rolleri oluşturuldu/kontrol edildi.');

        // 2. 'Burak' kullanıcısını oluştur
        // Eğer kullanıcı zaten varsa tekrar oluşturmaz.
        $burak = User::firstOrCreate(
            ['email' => 'burak@gmail.com'], // E-posta adresi ile kontrol et
            [
                'name' => 'Burak',
                'password' => Hash::make('1234'), // Şifreyi hash'lemeyi unutmayın
            ]
        );

        $this->command->info('Burak kullanıcısı oluşturuldu/kontrol edildi.');


        // 3. 'Burak' kullanıcısına 'admin' rolünü ata
        // Zaten atanmışsa tekrar atamaz.
        if (!$burak->hasRole('admin')) {
            $burak->assignRole('admin');
            $this->command->info('Burak kullanıcısına admin rolü atandı.');
        } else {
            $this->command->info('Burak kullanıcısının zaten admin rolü var.');
        }
    }
}

