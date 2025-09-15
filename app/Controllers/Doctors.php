<?php

namespace App\Controllers;

use App\Models\DoctorScheduleModel;
use App\Models\UserModel;

class Doctors extends BaseController
{
    public function index()
    {
        $userModel = new UserModel;
        $listDoctor = $userModel->where('role', 'doctor')->findAll();
        $data = ['title' => 'List Dokter', 'listDoctor' => $listDoctor];

        return view('backoffice/list-dokter', $data);
    }

    public function schedule($doctorId)
    {
        $doctorModel = new UserModel();
        $scheduleModel = new DoctorScheduleModel();
        $doctor    = $doctorModel->find($doctorId);
        $schedules = $scheduleModel
            ->where('doctor_id', $doctorId)
            ->orderBy('schedule_date', 'DESC')   // newest date first
            ->orderBy('start_time', 'ASC')       // earlier times first within that date
            ->findAll();
        $data = [
            'title'     => 'Jadwal Dokter',
            'doctor'    => $doctor,
            'schedules' => $schedules,
        ];

        return view('backoffice/list-jadwal-dokter', $data);
    }

    public function edit($doctorId)
    {
        $userModel = new UserModel();
        $doctor = $userModel->find($doctorId);

        if (!$doctor) {
            return redirect()->to('/doctors')->with('error', 'Dokter tidak ditemukan');
        }

        $data = [
            'title'  => 'Edit Dokter',
            'doctor' => $doctor,
        ];

        return view('backoffice/edit-dokter', $data);
    }

    public function update($doctorId)
    {
        $userModel = new UserModel();

        $validationRules = [
            'name'  => 'required|min_length[3]',
            'whatsapp_number' => 'required|min_length[10]',
        ];

        if (! $this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'            => $this->request->getPost('name'),
            'whatsapp_number' => $this->request->getPost('whatsapp_number'),
        ];

        $userModel->update($doctorId, $data);

        return redirect()->to('backoffice/doctors')->with('success', 'Dokter berhasil diperbarui');
    }

    public function createSchedule($doctorId)
    {
        $doctorModel = new UserModel();
        $doctor = $doctorModel->find($doctorId);

        $data = [
            'title' => 'Tambah Jadwal Dokter',
            'doctor' => $doctor
        ];

        return view('backoffice/tambah-jadwal-dokter', $data);
    }

    public function storeSchedule($doctorId)
    {
        $scheduleModel = new DoctorScheduleModel();
        $validation = \Config\Services::validation();

        $rules = [
            'schedule_date' => [
                'rules' => 'required|valid_date[Y-m-d]',
                'errors' => [
                    'required'   => 'Tanggal jadwal wajib diisi.',
                    'valid_date' => 'Tanggal jadwal tidak valid.'
                ]
            ],
            'start_time' => [
                'rules' => 'required|valid_date[H:i]',
                'errors' => [
                    'required'   => 'Jam mulai wajib diisi.',
                    'valid_date' => 'Jam mulai tidak valid.'
                ]
            ],
            'end_time' => [
                'rules' => 'required|valid_date[H:i]',
                'errors' => [
                    'required'   => 'Jam selesai wajib diisi.',
                    'valid_date' => 'Jam selesai tidak valid.'
                ]
            ]
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Combine date and time to check if in the past
        $scheduleDate = $this->request->getPost('schedule_date');
        $startTime    = $this->request->getPost('start_time');
        $endTime      = $this->request->getPost('end_time');

        $startDateTime = strtotime("$scheduleDate $startTime");
        $endDateTime   = strtotime("$scheduleDate $endTime");
        $now           = time();

        if ($startDateTime < $now) {
            return redirect()->back()->withInput()->with('errors', [
                'start_time' => 'Jam mulai tidak boleh di masa lalu.'
            ]);
        }

        if ($endDateTime <= $startDateTime) {
            return redirect()->back()->withInput()->with('errors', [
                'end_time' => 'Jam selesai harus lebih besar dari jam mulai.'
            ]);
        }

        // 🔎 Check for duplicate schedule
        $exists = $scheduleModel
            ->where('doctor_id', $doctorId)
            ->where('schedule_date', $scheduleDate)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('errors', [
                'duplicate' => 'Jadwal ini sudah ada untuk dokter tersebut.'
            ]);
        }

        // Passed validation
        $data = [
            'doctor_id'     => $doctorId,
            'schedule_date' => $scheduleDate,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
            'is_available'  => 1
        ];

        $scheduleModel->insert($data);

        return redirect()->to(base_url("backoffice/doctors/schedule/$doctorId/create"))
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function deactivate($id)
    {
        $userModel = new UserModel();

        $doctor = $userModel->find($id);
        if (! $doctor) {
            return redirect()->back()->with('error', 'Dokter tidak ditemukan.');
        }

        $userModel->update($id, ['is_active' => 0]);

        return redirect()->to(base_url('backoffice/doctors'))
            ->with('success', 'Dokter berhasil dinonaktifkan.');
    }

    public function activate($id)
    {
        $userModel = new UserModel();

        $doctor = $userModel->find($id);
        if (! $doctor) {
            return redirect()->back()->with('error', 'Dokter tidak ditemukan.');
        }

        $userModel->update($id, ['is_active' => 1]);

        return redirect()->to(base_url('backoffice/doctors'))
            ->with('success', 'Dokter berhasil diaktifkan.');
    }
}
