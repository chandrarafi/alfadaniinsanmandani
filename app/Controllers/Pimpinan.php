<?php

namespace App\Controllers;

class Pimpinan extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        return view('pimpinan/dashboard', [
            'title' => 'Dashboard Pimpinan',
            'user' => $this->session->get()
        ]);
    }
}
