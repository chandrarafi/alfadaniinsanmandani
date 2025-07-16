<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Cek apakah user sudah login
        if (!$session->get('logged_in')) {
            // Jika belum login dan mencoba akses halaman yang butuh login, redirect ke login
            if (current_url() != base_url('auth')) {
                $session->set('last_page', current_url());
                return redirect()->to(base_url('auth'));
            }
        } else {
            // Jika sudah login
            $role = $session->get('role');

            // Jika mencoba akses halaman login, redirect sesuai role
            if (current_url() == base_url('auth')) {
                if ($role == 'admin') {
                    return redirect()->to(base_url('admin'));
                } else if ($role == 'pimpinan') {
                    return redirect()->to(base_url('pimpinan'));
                } else {
                    // Jika jamaah, redirect ke halaman terakhir atau home
                    return redirect()->to($session->get('last_page') ?? base_url());
                }
            }

            // Filter akses berdasarkan role
            if (isset($arguments[0])) {
                // Jika halaman hanya untuk role tertentu dan role user tidak sesuai
                if (!in_array($role, $arguments)) {
                    return redirect()->to(base_url());
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
