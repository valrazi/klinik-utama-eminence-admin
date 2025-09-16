<?php

namespace App\Controllers;

use App\Models\UserModel;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    private function redirectIfLoggedIn()
    {
        if (session()->get('isLoggedIn')) {
            if (session()->get('user_role') === 'patient') {
                return redirect()->to('/users/home');
            } else {
                return redirect()->to('/backoffice/dashboard');
            }
        }
        return null; // not logged in, continue
    }

    public function indexBackoffice()
    {
        $redirect = $this->redirectIfLoggedIn();
        if ($redirect) return $redirect;

        $data['title'] = "Login";
        return view('auth/login-backoffice', $data);
    }

    public function index()
    {
        $redirect = $this->redirectIfLoggedIn();
        if ($redirect) return $redirect;

        $data['title'] = "Login";
        return view('auth/login', $data);
    }

    public function register()
    {
        $redirect = $this->redirectIfLoggedIn();
        if ($redirect) return $redirect;

        $data['title'] = 'Register';
        return view('auth/register', $data);
    }

    public function create()
    {
        $validation = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'name is required'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'email is required',
                    'valid_email' => 'please add a valid email',
                    'is_unique' => 'email already exists'
                ]
            ],
            'whatsapp_number' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'whatsapp number is required',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required' => 'password is required',
                    'min_length' => 'minimal 4 character'
                ]
            ],
        ]);

        if (!$validation) {
            return view('auth/register', ['validation' => $this->validator]);
        } else {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $whatsapp_number = $this->request->getPost('whatsapp_number');
            $password = $this->request->getPost('password');

            $values = [
                'name' => $name,
                'email' => $email,
                'whatsapp_number' => $whatsapp_number,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];

            $user = new UserModel();
            $query = $user->insert($values);
            if (!$query) {
                return redirect()->back()
                    ->withInput()
                    ->with('fail', 'Register failed: ' . implode(', ', $user->errors()));
            }
            return redirect()->to(base_url('/auth/login'))->with('success', 'Account created succesfully');
        }
    }

    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[4]',
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', ['validation' => $this->validator]);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
            // Store session data
            session()->set([
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'user_email' => $user['email'],
                'user_role'  => $user['role'],
                'isLoggedIn' => true,
            ]);

            // Redirect based on role
            if ($user['role'] === 'patient') {
                return redirect()->to('/users/home');
            } else {
                return redirect()->to(base_url('/backoffice'));
            }
        }

        return redirect()->back()
            ->withInput()
            ->with('fail', 'Invalid email or password');
    }
    

    // 🚪 SIGN OUT (LOGOUT)
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/auth/login'))->with('success', 'You have been signed out');
    }

    
}
