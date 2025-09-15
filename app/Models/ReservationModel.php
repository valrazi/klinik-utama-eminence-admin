<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table            = 'reservations';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'patient_id',
        'staff_id',
        'service_id',
        'schedule_date',
        'start_time',
        'end_time',
        'status',
        'reschedule_of',
        'reminder_whatsapp_status',
        'reminder_whatsapp_sent_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    
    protected $updatedField  = '';  
    public function baseReservationQuery()
    {
        return $this
            ->select('
            reservations.*, 
            patient.name as patient_name, 
            patient.whatsapp_number as patient_whatsapp_number,
            patient.existing_patient as patient_existing,
            staff.name as staff_name,
        ')
            ->join('users as patient', 'patient.id = reservations.patient_id')
            ->join('users as staff', 'staff.id = reservations.staff_id');
    }
public function allWithDetail()
{
    return $this->baseReservationQuery()
        ->orderBy('schedule_date', 'ASC')   // oldest first
        ->orderBy('start_time', 'ASC')      // then earliest time in that day
        ->findAll();
}


    public function oneWithDetail($id)
    {
        return $this->baseReservationQuery()->find($id);
    }
}
