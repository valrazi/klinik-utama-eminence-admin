<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'            => 'Admin User',
            'email'           => 'admin@eminence.com',
            'whatsapp_number' => '081234567890',
            'password'        => password_hash('admin123', PASSWORD_DEFAULT), // ✅ hashed
            'role'            => 'administrator',
            'gender'          => 'male',
            'is_active'       => true,
            'existing_patient'=> false,
        ];

        // Insert into DB
        $this->db->table('users')->insert($data);
    }
}
