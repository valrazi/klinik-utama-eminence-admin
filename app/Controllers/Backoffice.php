<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\UserModel;

class Backoffice extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }
    public function index(): string
    {
        $userModel = new UserModel();
        $reservationModel = new ReservationModel();

        // Existing dashboard stats
        $totalPatients   = $userModel->where(['role' => 'patient'])->countAllResults();
        $newRsv          = $reservationModel->where(['status' => 'booked', 'reschedule_of' => null])->countAllResults();
        $rescheduledRsv  = $reservationModel->where(['status' => 'rescheduled'])->countAllResults();
        $cancelledRsv    = $reservationModel->where(['status' => 'cancelled'])->countAllResults();
        $completedRsv    = $reservationModel->where(['status' => 'completed'])->countAllResults();

        // Patient graph data
        $builder = $userModel->select("
    YEAR(created_at) as year,
    MONTH(created_at) as month,
    MIN(DATE_FORMAT(created_at, '%M %Y')) as month_name,
    SUM(CASE WHEN existing_patient = 0 AND role = 'patient' THEN 1 ELSE 0 END) as new_patient,
    SUM(CASE WHEN existing_patient = 1 AND role = 'patient' THEN 1 ELSE 0 END) as existing_patient
")
            ->where('role', 'patient')
            ->groupBy('year, month')
            ->orderBy('year, month', 'ASC')
            ->findAll();


        $labels = array_column($builder, 'month_name');
        $newPatientData = array_column($builder, 'new_patient');
        $existingPatientData = array_column($builder, 'existing_patient');

        $data = [
            'totalPatients'       => $totalPatients,
            'newRsv'              => $newRsv,
            'rescheduledRsv'      => $rescheduledRsv,
            'cancelledRsv'        => $cancelledRsv,
            'completedRsv'        => $completedRsv,
            'chartLabels'         => json_encode($labels),
            'chartNewPatients'    => json_encode($newPatientData),
            'chartExistingPatients' => json_encode($existingPatientData),
        ];

        return view('backoffice/home', $data);
    }


    public function addStaff()
    {
        $data = [
            'title' => 'Tambah Staff'
        ];
        return view('backoffice/tambah-staff', $data);
    }

    public function saveStaff()
    {
        $validationRules = [
            'namaLengkap'  => 'required|min_length[3]',
            'jenisKelamin' => 'required|in_list[male,female]',
            'role'         => 'required|in_list[doctor,therapist]',
            'nomorWhatsapp' => 'required|min_length[10]'
        ];

        if (! $this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel;

        $namaLengkap = $this->request->getPost('namaLengkap');
        $jenisKelamin = $this->request->getPost('jenisKelamin');
        $role = $this->request->getPost('role');
        $whatsappNumber = $this->request->getPost('nomorWhatsapp');

        // Generate email
        $safeName = strtolower(str_replace(' ', '_', $namaLengkap));
        $timestamp = time();
        $email = $safeName . '_' . $timestamp . '@eminence.com';

        $data = [
            'name'             => $namaLengkap,
            'gender'           => $jenisKelamin,
            'role'             => $role,
            'email'            => $email,
            'password'         => password_hash('eminence@2025', PASSWORD_DEFAULT),
            'whatsapp_number'  => $whatsappNumber,
            'is_active'        => 1,
            'existing_patient' => 0,
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('backoffice/tambah-staff')
                ->with('success', 'Staff berhasil ditambahkan');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan staff');
        }
    }
}
