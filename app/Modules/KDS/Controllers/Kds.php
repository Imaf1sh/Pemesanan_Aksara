<?php

namespace App\Modules\KDS\Controllers;

use App\Controllers\BaseController;

class Kds extends BaseController
{
    public function index()
    {
        $role = session()->get('role');
        if ($role === 'kasir') {
            return redirect()->to(base_url('pos'))->with('error', 'Akses Ditolak: Halaman KDS Dapur hanya untuk Admin dan Owner.');
        }
        return view('App\Modules\KDS\Views\kds_screen');
    }
}
