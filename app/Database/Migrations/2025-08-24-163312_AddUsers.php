<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

// id INT AUTO_INCREMENT PRIMARY KEY,
//   name VARCHAR(100),
//   email VARCHAR(100) UNIQUE,
//   phone VARCHAR(20),
//   role ENUM('patient', 'doctor', 'therapist') NOT NULL,
//   password VARCHAR(255),
//   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
class AddUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'whatsapp_number' => [
                'type' => 'VARCHAR',
                'constraint' => '15'
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['patient', 'doctor', 'therapist', 'administrator'],
                'default' => 'patient'
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female']
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true
            ],
            'existing_patient' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
