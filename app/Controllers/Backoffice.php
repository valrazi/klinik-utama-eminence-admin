<?php

namespace App\Controllers;

use App\Models\UserModel;

class Backoffice extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }
    public function index(): string
    {
        return view('backoffice/home');
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
