<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDoctorSchedule extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'doctor_id' => [
                'type' => 'INT',
            ],
            'schedule_date' => [
                'type' => 'DATE'
            ],
            'start_time' => [
                'type' => 'TIME'
            ],
            'end_time' => [
                'type' => 'TIME'
            ],
            'is_available' => [
                'type' => 'BOOLEAN',
                'default' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('doctor_id', 'users', 'id');
        $this->forge->createTable('doctor_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('doctor_schedules');
    }
}
