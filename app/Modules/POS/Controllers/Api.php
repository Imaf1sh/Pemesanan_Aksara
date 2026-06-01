<?php

namespace App\Modules\POS\Controllers;

use App\Controllers\BaseController;
use App\Modules\POS\Models\ProductModel;
use App\Modules\POS\Models\OrderModel;
use App\Modules\POS\Models\OrderItemModel;
use App\Modules\POS\Models\ShiftModel;
use App\Modules\POS\Models\ExpenseModel;
use App\Modules\POS\Models\RawMaterialModel;
use CodeIgniter\API\ResponseTrait;

class Api extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // Support both GET/POST actions
        $method = $this->request->getMethod();
        
        $productModel = new ProductModel();
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $shiftModel = new ShiftModel();
        $expenseModel = new ExpenseModel();
        $rawMaterialModel = new RawMaterialModel();

        // Auth check for non-public API actions
        $isLoggedIn = session()->get('isLoggedIn');

        if ($method === 'GET') {
            $action = $this->request->getGet('action') ?? 'orders';
            
            // Only products is public GET
            $isPublic = ($action === 'products' || $action === 'shift_status');
            if (!$isPublic && !$isLoggedIn) {
                return $this->failUnauthorized('Silakan login terlebih dahulu.');
            }

            if ($action === 'products') {
                $products = $productModel->findAll();
                return $this->respond($products);
            } 
            
            if ($action === 'shift_status') {
                $shift = $shiftModel->find(1);
                if (!$shift) {
                    $shift = [
                        'status' => 'closed',
                        'initial_cash' => 0,
                        'open_time' => null,
                        'close_time' => null
                    ];
                }
                return $this->respond($shift);
            } 

            if ($action === 'expenses') {
                $expenses = $expenseModel->orderBy('created_at', 'DESC')->findAll();
                return $this->respond($expenses);
            }

            if ($action === 'raw_materials') {
                // Self-healing seed if empty
                if ($rawMaterialModel->countAllResults() === 0) {
                    $rawMaterialModel->insertBatch([
                        ['name' => 'Biji Kopi Gayo (Arabica)', 'stock' => 5000.00, 'unit' => 'gram'],
                        ['name' => 'Susu UHT Full Cream', 'stock' => 10000.00, 'unit' => 'ml'],
                        ['name' => 'Matcha Powder Premium', 'stock' => 1000.00, 'unit' => 'gram'],
                        ['name' => 'Chocolate Powder Signature', 'stock' => 2000.00, 'unit' => 'gram'],
                        ['name' => 'Butter (Croissant Ingredient)', 'stock' => 3000.00, 'unit' => 'gram'],
                    ]);
                }
                $materials = $rawMaterialModel->orderBy('name', 'ASC')->findAll();
                return $this->respond($materials);
            }
            
            // Default: Fetch all orders with items
            $orders = $orderModel->orderBy('created_at', 'DESC')->findAll();
            foreach ($orders as &$order) {
                $order['items'] = $orderItemModel->where('order_id', $order['id'])->findAll();
                $order['timestamp'] = $order['created_at'];
            }
            return $this->respond($orders);
        }

        if ($method === 'POST') {
            // Get JSON Input
            $input = $this->request->getJSON(true);
            
            $action = $input['action'] ?? 'create';

            // Only 'create' (placing order) is public POST
            $isPublic = ($action === 'create');
            if (!$isPublic && !$isLoggedIn) {
                return $this->failUnauthorized('Silakan login terlebih dahulu.');
            }

            if ($action === 'update_status') {
                $orderId = $input['id'] ?? null;
                $newStatus = $input['status'] ?? null;

                if ($orderId && $newStatus) {
                    $order = $orderModel->find($orderId);
                    if ($order) {
                        $orderModel->update($orderId, ['status' => $newStatus]);
                        return $this->respond(['success' => true, 'message' => 'Status updated']);
                    }
                }
                return $this->failNotFound('Order not found or invalid data');
            } 
            
            if ($action === 'open_shift') {
                $initialCash = $input['initial_cash'] ?? 0;
                
                // Ensure row 1 exists or update it
                $shift = $shiftModel->find(1);
                $shiftData = [
                    'id'           => 1,
                    'status'       => 'open',
                    'initial_cash' => $initialCash,
                    'open_time'    => date('Y-m-d H:i:s'),
                    'close_time'   => null
                ];

                if ($shift) {
                    $shiftModel->update(1, $shiftData);
                } else {
                    $shiftModel->insert($shiftData);
                }

                return $this->respond(['success' => true, 'message' => 'Shift opened']);
            } 
            
            if ($action === 'close_shift') {
                $shift = $shiftModel->find(1);
                if ($shift) {
                    $shiftModel->update(1, [
                        'status' => 'closed',
                        'close_time' => date('Y-m-d H:i:s')
                    ]);
                    return $this->respond(['success' => true, 'message' => 'Shift closed']);
                }
                return $this->fail('No active shift found');
            } 

            if ($action === 'create_expense') {
                $amount = $input['amount'] ?? 0;
                $description = $input['description'] ?? '';

                if (empty($description) || $amount <= 0) {
                    return $this->fail('Keterangan dan jumlah pengeluaran tidak valid.');
                }

                $expenseModel->insert([
                    'amount'      => $amount,
                    'description' => $description,
                    'created_at'  => date('Y-m-d H:i:s')
                ]);

                return $this->respond(['success' => true, 'message' => 'Pengeluaran berhasil dicatat.']);
            }

            if ($action === 'update_raw_material') {
                $id = $input['id'] ?? null;
                $name = $input['name'] ?? '';
                $stock = $input['stock'] ?? 0.00;
                $unit = $input['unit'] ?? '';

                if (empty($name) || empty($unit)) {
                    return $this->fail('Nama dan satuan bahan baku wajib diisi.');
                }

                $data = [
                    'name'  => $name,
                    'stock' => $stock,
                    'unit'  => $unit
                ];

                if ($id) {
                    $rawMaterialModel->update($id, $data);
                } else {
                    $rawMaterialModel->insert($data);
                }

                return $this->respond(['success' => true, 'message' => 'Bahan baku berhasil disimpan.']);
            }
            
            // Create a new order
            $orderId = uniqid('ord_');
            $customerName = $input['customer_name'] ?? 'Guest';
            $items = $input['items'] ?? [];
            $total = $input['total'] ?? 0;
            $paymentMethod = $input['payment_method'] ?? 'Cash';
            $orderType = $input['order_type'] ?? 'Dine In';

            // Start Transaction to guarantee data integrity
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Insert into orders
            $newOrder = [
                'id'             => $orderId,
                'customer_name'  => $customerName,
                'total'          => $total,
                'payment_method' => $paymentMethod,
                'order_type'     => $orderType,
                'status'         => 'pending',
                'created_at'     => date('Y-m-d H:i:s')
            ];
            $orderModel->insert($newOrder);

            // 2. Insert into order items & reduce stock
            foreach ($items as $item) {
                $productId = $item['id'] ?? 1;
                $price = $item['price'] ?? 0;
                $qty = $item['qty'] ?? 1;
                $notes = $item['notes'] ?? '';
                
                // Get product name if not provided
                $product = $productModel->find($productId);
                $name = $item['name'] ?? ($item['title'] ?? ($product['name'] ?? 'Item'));

                $orderItemModel->insert([
                    'order_id'   => $orderId,
                    'product_id' => $productId,
                    'name'       => $name,
                    'price'      => $price,
                    'qty'        => $qty,
                    'notes'      => $notes
                ]);

                // Reduce stock
                if ($product) {
                    $newStock = max(0, $product['stock'] - $qty);
                    $productModel->update($productId, ['stock' => $newStock]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Failed to process order');
            }

            // Return in same exact format
            $newOrder['items'] = $orderItemModel->where('order_id', $orderId)->findAll();
            $newOrder['timestamp'] = $newOrder['created_at'];

            return $this->respond(['success' => true, 'order' => $newOrder]);
        }

        return $this->fail('Method not allowed', 405);
    }
}
