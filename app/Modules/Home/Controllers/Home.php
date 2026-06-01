<?php

namespace App\Modules\Home\Controllers;

use App\Controllers\BaseController;
use App\Modules\POS\Models\ProductModel;

class Home extends BaseController
{
    public function index(): string
    {
        $productModel = new ProductModel();
        
        $data = [
            'coffee'    => $productModel->where('category', 'coffee')->findAll(),
            'nonCoffee' => $productModel->where('category', 'non-coffee')->findAll(),
            'snack'     => $productModel->where('category', 'snack')->findAll(),
        ];

        return view('App\Modules\Home\Views\customer_menu', $data);
    }
}
