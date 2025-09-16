<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Not logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'))
                             ->with('fail', 'Please login first.');
        }

        // Role restriction (optional)
        if ($arguments && in_array('admin', $arguments) && $session->get('user_role') !== 'admin') {
            return redirect()->to(base_url('auth/login'))
                             ->with('fail', 'You are not allowed to access this page.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}
