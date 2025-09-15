<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddReservations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'patient_id' => [
                'type' => 'INT'
            ],
            'staff_id' => [
                'type' => 'INT'
            ],
            'service' => [
                'type'       => 'VARCHAR',
                'constraint' => 255, // FIXED
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
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['booked', 'cancelled', 'completed', 'rescheduled'],
                'default' => 'booked'
            ],
            'reschedule_of' => [
                'type' => 'INT',
                'null' => true
            ],
            'reminder_whatsapp_sent_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'reminder_whatsapp_sent_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'sent', 'failed'],
                'default' => 'pending'
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('patient_id', 'users', 'id');
        $this->forge->addForeignKey('staff_id', 'users', 'id');
        $this->forge->addForeignKey('reschedule_of', 'reservations', 'id');
        $this->forge->createTable('reservations');
    }

    public function down()
    {
        $this->forge->dropTable('reservations');
    }
}
