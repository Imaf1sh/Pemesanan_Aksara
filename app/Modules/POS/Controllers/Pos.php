<?php

namespace App\Modules\POS\Controllers;

use App\Controllers\BaseController;

class Pos extends BaseController
{
    public function index()
    {
        $role = session()->get('role') ?? 'kasir';
        
        switch ($role) {
            case 'admin':
                return view('App\Modules\POS\Views\pos_admin');
            case 'owner':
                return view('App\Modules\POS\Views\pos_owner');
            case 'kasir':
            default:
                return view('App\Modules\POS\Views\pos_kasir');
        }
    }
}
