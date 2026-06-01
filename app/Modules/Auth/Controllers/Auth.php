<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Modules\Auth\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('pos'));
        }
        return view('App\Modules\Auth\Views\login');
    }

    public function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            $session->setFlashdata('error', 'Username dan password wajib diisi.');
            return redirect()->back();
        }

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Set Session
                $sessionData = [
                    'userId'     => $user['id'],
                    'username'   => $user['username'],
                    'name'       => $user['name'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                // Redirect based on role
                return redirect()->to(base_url('pos'));
            }
        }

        $session->setFlashdata('error', 'Username atau password salah.');
        return redirect()->back();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
