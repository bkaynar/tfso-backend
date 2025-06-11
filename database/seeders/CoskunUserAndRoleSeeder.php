<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Kendi User modelinizin namespace'ini doğru yazdığınızdan emin olun
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CoskunUserAndRoleSeeder extends Seeder
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

        // 2. 'Coskun' kullanıcısını oluştur
        // Eğer kullanıcı zaten varsa tekrar oluşturmaz.
        $coskun = User::firstOrCreate(
            ['email' => 'coskun@tfsoisrael.com'], // E-posta adresi ile kontrol et
            [
                'name' => 'Coskun',
                'password' => Hash::make('1234'), // Şifreyi hash'lemeyi unutmayın
            ]
        );

        $this->command->info('Coskun kullanıcısı oluşturuldu/kontrol edildi.');


        // 3. 'Coskun' kullanıcısına 'admin' rolünü ata
        // Zaten atanmışsa tekrar atamaz.
        if (!$coskun->hasRole('admin')) {
            $coskun->assignRole('admin');
            $this->command->info('Coskun kullanıcısına admin rolü atandı.');
        } else {
            $this->command->info('Coskun kullanıcısının zaten admin rolü var.');
        }
    }
}
