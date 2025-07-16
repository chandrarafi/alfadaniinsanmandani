<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $session;
    protected $userModel;
    protected $email;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
        $this->email = \Config\Services::email();
    }

    public function index()
    {
        // Cek parameter redirect
        $redirect = $this->request->getGet('redirect');
        if ($redirect) {
            $this->session->set('last_page', base_url($redirect));
        }

        return view('auth/login', [
            'title' => 'Login'
        ]);
    }

    public function register()
    {
        return view('auth/register', [
            'title' => 'Register'
        ]);
    }

    public function processLogin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'email' => [
                'required' => 'Email atau username harus diisi'
            ],
            'password' => [
                'required' => 'Password harus diisi'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $emailOrUsername = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cek apakah input adalah email atau username
        $isEmail = filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL);

        // Cari user berdasarkan email atau username
        if ($isEmail) {
            $user = $this->userModel->where('email', $emailOrUsername)->first();
        } else {
            $user = $this->userModel->where('username', $emailOrUsername)->first();
        }

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email atau username tidak terdaftar'
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Password salah'
            ]);
        }

        if ($user['status'] != 1 && $user['role'] == 'jamaah') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Akun belum diverifikasi, silahkan cek email Anda'
            ]);
        }

        $sessionData = [
            'id' => $user['id'],
            'username' => $user['username'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role' => $user['role'],
            'logged_in' => true
        ];

        $this->session->set($sessionData);

        $redirectUrl = '';
        switch ($user['role']) {
            case 'admin':
                $redirectUrl = base_url('admin');
                break;
            case 'pimpinan':
                $redirectUrl = base_url('pimpinan');
                break;
            case 'jamaah':
                $redirectUrl = $this->session->get('last_page') ?? base_url();
                break;
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Login berhasil',
            'redirect' => $redirectUrl
        ]);
    }

    public function processRegister()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'nama' => 'required',
            'username' => 'required|is_unique[user.username]',
            'email' => 'required|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama lengkap harus diisi'
            ],
            'username' => [
                'required' => 'Username harus diisi',
                'is_unique' => 'Username sudah digunakan, silakan pilih username lain'
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email sudah terdaftar, silakan gunakan email lain'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak sesuai dengan password'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $token = bin2hex(random_bytes(32));
        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'jamaah',
            'status' => 0
        ];

        $this->userModel->insert($data);
        $userId = $this->userModel->getInsertID();

        // Simpan token verifikasi
        $this->userModel->saveVerificationToken($userId, $token);

        // Kirim email verifikasi
        $this->sendVerificationEmail($data['email'], $token);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Pendaftaran berhasil, silahkan cek email Anda untuk verifikasi'
        ]);
    }

    private function sendVerificationEmail($email, $token)
    {
        $this->email->setFrom('noreply@alfadani.com', 'Alfadani Insan Mandani');
        $this->email->setTo($email);
        $this->email->setSubject('Verifikasi Akun Alfadani Insan Mandani');

        $verificationLink = base_url('auth/verify/' . $token);

        // Template email dengan tema travel haji dan umroh
        $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Verifikasi Akun Alfadani Insan Mandani</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
            }
            .email-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .email-header {
                background-color: #006400;
                color: white;
                padding: 20px;
                text-align: center;
            }
            .email-body {
                padding: 30px;
            }
            .email-footer {
                background-color: #f5f5f5;
                padding: 15px;
                text-align: center;
                font-size: 12px;
                color: #666;
                border-top: 1px solid #ddd;
            }
            .btn {
                display: inline-block;
                padding: 12px 30px;
                background-color: #006400;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                margin: 20px 0;
            }
            .btn:hover {
                background-color: #004d00;
            }
            .banner-img {
                width: 100%;
                height: auto;
                max-height: 200px;
                object-fit: cover;
            }
            .kaaba-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 20px;
                display: block;
            }
            .divider {
                border-top: 1px solid #ddd;
                margin: 20px 0;
            }
            .highlight {
                background-color: rgba(0, 100, 0, 0.1);
                border-left: 4px solid #006400;
                padding: 15px;
                margin: 20px 0;
            }
            .social-links {
                margin-top: 20px;
            }
            .social-links a {
                display: inline-block;
                margin: 0 10px;
                color: #006400;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                <h1>Alfadani Insan Mandani</h1>
                <p>Travel Haji & Umroh Terpercaya</p>
            </div>
            
            <img src="https://source.unsplash.com/featured/?kaaba,mecca" class="banner-img" alt="Kaaba">
            
            <div class="email-body">
                <img src="https://cdn-icons-png.flaticon.com/512/5175/5175821.png" class="kaaba-icon" alt="Kaaba Icon">
                
                <h2 style="text-align: center; color: #006400;">Verifikasi Akun Anda</h2>
                
                <p>Assalamu\'alaikum Wr. Wb.</p>
                
                <p>Terima kasih telah mendaftar di <strong>Alfadani Insan Mandani</strong>, partner terpercaya untuk perjalanan ibadah haji dan umroh Anda.</p>
                
                <div class="highlight">
                    <p>Untuk menyelesaikan pendaftaran akun Anda, silakan klik tombol di bawah ini:</p>
                </div>
                
                <div style="text-align: center;">
                    <a href="' . $verificationLink . '" class="btn">Verifikasi Akun Saya</a>
                </div>
                
                <p>Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan URL berikut ke browser Anda:</p>
                <p><a href="' . $verificationLink . '">' . $verificationLink . '</a></p>
                
                <div class="divider"></div>
                
                <p><strong>Penting:</strong> Link verifikasi ini akan kedaluwarsa dalam 24 jam.</p>
                
                <p>Jika Anda tidak merasa mendaftar untuk layanan ini, Anda dapat mengabaikan email ini.</p>
                
                <p>Wassalamu\'alaikum Wr. Wb.</p>
                
                <p>Hormat Kami,<br>Tim Alfadani Insan Mandani</p>
                
                <div class="social-links">
                    <a href="#">Facebook</a> |
                    <a href="#">Instagram</a> |
                    <a href="#">WhatsApp</a> |
                    <a href="#">Website</a>
                </div>
            </div>
            
            <div class="email-footer">
                <p>&copy; ' . date('Y') . ' Alfadani Insan Mandani. Semua Hak Dilindungi.</p>
                <p>Jl. Contoh No. 123, Kota, Indonesia</p>
            </div>
        </div>
    </body>
    </html>
    ';

        $this->email->setMessage($message);
        $this->email->send();
    }

    public function verify($token)
    {
        $user = $this->userModel->verifyToken($token);

        if ($user) {
            $this->userModel->update($user['id'], ['status' => 1]);
            $this->userModel->deleteVerificationToken($token);

            return redirect()->to(base_url('auth'))->with('success', 'Verifikasi akun berhasil, silahkan login');
        }

        return redirect()->to(base_url('auth'))->with('error', 'Token verifikasi tidak valid atau sudah kadaluarsa');
    }

    public function logout()
    {
        $this->session->remove('last_page');
        $this->session->destroy();
        return redirect()->to(base_url('auth'));
    }
}
