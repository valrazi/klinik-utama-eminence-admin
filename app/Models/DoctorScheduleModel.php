<?php namespace App\Models;

use CodeIgniter\Model;

class DoctorScheduleModel extends Model
{
    protected $table            = 'doctor_schedules';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'doctor_id', 'schedule_date', 'start_time', 'end_time', 'is_available'
    ];
    public    $timestamps       = false;
}
