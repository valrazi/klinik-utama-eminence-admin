<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

// id INT AUTO_INCREMENT PRIMARY KEY,
//   patient_id INT NOT NULL,
//   staff_id INT NOT NULL,
//   service_id INT NOT NULL,
//   schedule_date DATE NOT NULL,
//   start_time TIME NOT NULL,
//   end_time TIME NOT NULL,
//   status ENUM('booked','cancelled','completed') DEFAULT 'booked',
//   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
// reschedule_of int nullable
// 
//   FOREIGN KEY (patient_id) REFERENCES users(id),
//   FOREIGN KEY (staff_id) REFERENCES users(id),
//   FOREIGN KEY (service_id) REFERENCES services(id)
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
                'type' => 'VARCHAR'
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
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('patient_id', 'users', 'id');
        $this->forge->addForeignKey('staff_id', 'users', 'id');
        $this->forge->createTable('reservations');
        $this->forge->addForeignKey('reschedule_of', 'reservations', 'id');
    }

    public function down()
    {
        $this->forge->dropTable('reservations');
    }
}
