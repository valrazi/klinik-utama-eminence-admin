<?php

namespace App\Controllers;

use App\Models\DoctorScheduleModel;
use App\Models\UserModel;

class Therapists extends BaseController
{
    public function index()
    {
        $userModel = new UserModel;
        $listTherapist = $userModel->where('role', 'therapist')->findAll();
        $data = ['title' => 'List Therapist', 'listTherapist' => $listTherapist];

        return view('backoffice/list-therapist', $data);
    }



    public function edit($doctorId)
    {
        $userModel = new UserModel();
        $doctor = $userModel->find($doctorId);

        if (!$doctor) {
            return redirect()->to('/doctors')->with('error', 'Therapist tidak ditemukan');
        }

        $data = [
            'title'  => 'Edit Therapist',
            'doctor' => $doctor,
        ];

        return view('backoffice/edit-therapist', $data);
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

        return redirect()->to(base_url('backoffice/therapists'))
            ->with('success', 'Therapist berhasil diperbarui');
    }

   
    public function deactivate($id)
    {
        $userModel = new UserModel();

        $doctor = $userModel->find($id);
        if (! $doctor) {
            return redirect()->back()->with('error', 'Therapist tidak ditemukan.');
        }

        $userModel->update($id, ['is_active' => 0]);

        return redirect()->to(base_url('backoffice/therapists'))
            ->with('success', 'Therapist berhasil dinonaktifkan.');
    }

    public function activate($id)
    {
        $userModel = new UserModel();

        $doctor = $userModel->find($id);
        if (! $doctor) {
            return redirect()->back()->with('error', 'Therapist tidak ditemukan.');
        }

        $userModel->update($id, ['is_active' => 1]);

        return redirect()->to(base_url('backoffice/therapists'))
            ->with('success', 'Therapist berhasil diaktifkan.');
    }
}
