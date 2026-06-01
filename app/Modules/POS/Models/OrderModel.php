<?php

namespace App\Modules\POS\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false; // We use custom unique strings ord_...
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'customer_name', 'total', 'payment_method', 'order_type', 'status', 'created_at'];

    // Dates
    protected $useTimestamps = false; // Manually assigned timestamp/created_at
}
