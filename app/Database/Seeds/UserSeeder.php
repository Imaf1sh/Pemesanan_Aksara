<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        // Clear existing
        $builder->truncate();

        $users = [
            [
                'username'   => 'kasir',
                'password'   => password_hash('kasir123', PASSWORD_DEFAULT),
                'name'       => 'Ahmad (Kasir)',
                'role'       => 'kasir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'admin',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'name'       => 'Dewi (Admin)',
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'owner',
                'password'   => password_hash('owner123', PASSWORD_DEFAULT),
                'name'       => 'Budi (Owner)',
                'role'       => 'owner',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $builder->insertBatch($users);
        echo "USERS_SEEDED: Successfully seeded 3 user roles.\n";
    }
}
