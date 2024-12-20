<?php

namespace Database\Seeders;

use App\Models\ISP;
use App\Models\User;
use App\Models\Loket;
use App\Models\Cabang;
use App\Models\Teknisi;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Cabang::create([
            'nm_cabang' => 'gpm',
            'alamat_cabang' => 'gpm',
            'username_mikrotik' => 'admin',
            'ip_mikrotik' => '10.123.2.172:8728',
            'password_mikrotik' => 'admin',
            'kd_isp' => 1,
        ]);

        // Buat user kedua dan assign role 'admin'
        $user1 = new User();
        $user1->name = 'admin';
        $user1->email = 'admin@gmail.com';
        $user1->password = bcrypt('12341234');
        $user1->kd_role = 'super-admin';
        $user1->save();

        // masukkan rolenya
        $user1->assignRole('super-admin');

        // Buat user pertama dan assign role 'isp'
        $user2 = new User();
        $user2->name = 'isp';
        $user2->email = 'isp@gmail.com';
        $user2->password = bcrypt('12341234');
        $user2->kd_role = 'isp';
        $user2->save();

        // masukkan rolenya
        $user2->assignRole('isp');

        $useriSP = new ISP();
        $useriSP->nm_isp = 'ISP Satu';
        $useriSP->nm_brand = 'Brand Satu';
        $useriSP->alamat = 'Banyuwangi';
        $useriSP->no_telp = '08123456789';
        $useriSP->logo = "{{ asset('assets/images/logos/LogoMySemesta.png') }}";
        $useriSP->kd_user = $user2->id;
        $useriSP->save();


        // Buat user pertama dan assign role 'teknisi'
        $user3 = new User();
        $user3->name = 'teknisi';
        $user3->email = 'teknisi@gmail.com';
        $user3->password = bcrypt('12341234');
        $user3->kd_role = 'teknisi';
        $user3->save();

        // masukkan rolenya
        $user3->assignRole('teknisi');

        $userTeknisi = new Teknisi();
        $userTeknisi->nm_teknisi = 'Teknisi Satu';
        $userTeknisi->t_lahir = 'Jakarta';
        $userTeknisi->tgl_lahir = '1990-01-01';
        $userTeknisi->tgl_aktif = '2020-01-01';
        $userTeknisi->alamat_teknisi = 'Jl. Contoh No. 123';
        $userTeknisi->no_telp = '081234567890';
        $userTeknisi->kd_user = $user3->id;
        $userTeknisi->save();

        // Buat user keempat
        $user4 = new User();
        $user4->name = 'user4';
        $user4->email = 'user4@gmail.com';
        $user4->password = bcrypt('12341234');
        $user4->kd_role = 'loket';
        $user4->save();
        $user4->assignRole('loket');

        // Tambahkan data ke tabel lokets
        $loket1 = new Loket();
        $loket1->kd_user = $user4->id;
        $loket1->kd_cabang = 1;
        $loket1->nm_loket = 'Loket A';
        $loket1->alamat_loket = 'Alamat Loket A';
        $loket1->jenis_komisi = 'fixed';
        $loket1->jml_komisi = 100000;
        $loket1->save();

        // Buat user keempat
        $user5 = User::create([
            'name' => 'pelanggan',
            'email' => 'pelanggan@gmail.com',
            'password' => bcrypt('12341234'),
            'kd_role' => 'pelanggan',
        ]);

        $user5->assignRole('pelanggan');

        Pelanggan::create([
            'kd_user' => $user5->id,
            'tgl_pemasangan' => now(),
            'jenis_identitas' => 'KTP',
            'no_identitas' => '1234567890123456',
            'nm_pelanggan' => 'Pelanggan A',
            't_lahir' => 'Jakarta',
            'tgl_lahir' => now(),
            'pekerjaan' => 'Karyawan Swasta',
            'alamat' => 'Alamat Pelanggan A',
            'no_telp' => '08123456789',
            'kd_cabang' => 1,
            'kd_paket' => 1,
            'kd_loket' => 1,
            'lat' => '-6.2088',
            'long' => '106.8456',
            'foto_rumah' => 'foto_rumah_a.jpg',
            'username_pppoe' => 'user_pppoe_a',
            'password_pppoe' => bcrypt('password_pppoe_a'),
            'service_pppoe' => 'internet',
            'profile_pppoe' => 'basic',
        ]);

    }
}
