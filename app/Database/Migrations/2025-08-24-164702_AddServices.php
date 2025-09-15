<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
// id INT AUTO_INCREMENT PRIMARY KEY,
//   name ENUM('injury', 'performance', 'massage') NOT NULL,
//   duration INT DEFAULT 60  -- duration in minutes
class AddServices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'ENUM',
                'constraint' => ['injury', 'performance', 'massage'],
            ],
            'duration' => [
                'type' => 'INT',
                'default' => 60
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('services');
    }

    public function down()
    {
        $this->forge->dropTable('services');
    }
}
