<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->delete();

        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Алексей', 'surname' => 'Малышкин', 'middle_name'=>'Андреевич', 'login'=>'mapleart', 'is_admin'=>1, 'password' => Hash::make('pass1'), 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Александр', 'surname' => 'Пушкин', 'middle_name'=>'Сергеевич', 'login'=>'pushkin', 'is_admin'=>0, 'password' => Hash::make('pass2'), 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Лев', 'surname' => 'Толстой', 'middle_name'=>'Николаевич', 'login'=>'tolsoi', 'is_admin'=>0, 'password' => Hash::make('pass3'), 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Федор', 'surname' => 'Достоевский', 'middle_name'=>'Михайлович', 'login'=>'dostoev', 'is_admin'=>0, 'password' => Hash::make('pass4'), 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'name' => 'Антон', 'surname' => 'Чехов', 'middle_name'=>'Павлович', 'login'=>'checkov', 'is_admin'=>0, 'password' => Hash::make('pass5'), 'created_at' => date('Y-m-d H:i:s')],

        ]);

    }
}
