<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        $backupDir = ROOTPATH . 'vanilla_backup/';

        // 1. SEED PRODUCTS
        $productsFile = $backupDir . 'products.json';
        if (file_exists($productsFile)) {
            $productsData = json_decode(file_get_contents($productsFile), true);
            if (!empty($productsData)) {
                $db = \Config\Database::connect();
                $builder = $db->table('products');
                
                // Clear existing first
                $builder->truncate();
                
                foreach ($productsData as $p) {
                    // Match the database table columns
                    $builder->insert([
                        'id'         => $p['id'],
                        'name'       => $p['name'],
                        'price'      => $p['price'],
                        'category'   => $p['category'],
                        'img'        => $p['img'],
                        'stock'      => $p['stock'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                echo "PRODUCTS_SEEDED: Successfully imported " . count($productsData) . " products.\n";
            }
        }

        // 2. SEED ORDERS & ORDER ITEMS
        $ordersFile = $backupDir . 'orders.json';
        if (file_exists($ordersFile)) {
            $ordersData = json_decode(file_get_contents($ordersFile), true);
            if (!empty($ordersData)) {
                $db = \Config\Database::connect();
                $orderBuilder = $db->table('orders');
                $itemBuilder = $db->table('order_items');

                // Clear existing
                // Since there is a foreign key, we disable foreign keys, truncate, and re-enable
                $db->query("SET FOREIGN_KEY_CHECKS = 0;");
                $orderBuilder->truncate();
                $itemBuilder->truncate();
                $db->query("SET FOREIGN_KEY_CHECKS = 1;");

                $orderCount = 0;
                $itemCount = 0;

                foreach ($ordersData as $o) {
                    // Insert order
                    $orderBuilder->insert([
                        'id'             => $o['id'],
                        'customer_name'  => isset($o['customer_name']) ? $o['customer_name'] : 'Guest',
                        'total'          => $o['total'],
                        'payment_method' => isset($o['payment_method']) ? $o['payment_method'] : 'Cash',
                        'order_type'     => isset($o['order_type']) ? $o['order_type'] : 'Dine In',
                        'status'         => isset($o['status']) ? $o['status'] : 'pending',
                        'created_at'     => isset($o['timestamp']) ? $o['timestamp'] : date('Y-m-d H:i:s'),
                    ]);
                    $orderCount++;

                    // Insert order items
                    if (isset($o['items']) && is_array($o['items'])) {
                        foreach ($o['items'] as $item) {
                            $itemName = isset($item['name']) ? $item['name'] : (isset($item['title']) ? $item['title'] : 'Item');
                            $itemBuilder->insert([
                                'order_id'   => $o['id'],
                                'product_id' => isset($item['id']) ? $item['id'] : 1,
                                'name'       => $itemName,
                                'price'      => $item['price'],
                                'qty'        => $item['qty'],
                                'notes'      => isset($item['notes']) ? $item['notes'] : '',
                            ]);
                            $itemCount++;
                        }
                    }
                }
                echo "ORDERS_SEEDED: Successfully imported $orderCount orders and $itemCount order items.\n";
            }
        }

        // 3. SEED INITIAL SHIFT (CLOSED)
        $shiftFile = $backupDir . 'shift.json';
        $db = \Config\Database::connect();
        $shiftBuilder = $db->table('shifts');
        $shiftBuilder->truncate();
        
        $status = 'closed';
        $initialCash = 0;
        $openTime = null;
        $closeTime = null;

        if (file_exists($shiftFile)) {
            $shiftData = json_decode(file_get_contents($shiftFile), true);
            if ($shiftData) {
                $status = isset($shiftData['status']) ? $shiftData['status'] : 'closed';
                $initialCash = isset($shiftData['initial_cash']) ? $shiftData['initial_cash'] : 0;
                $openTime = isset($shiftData['open_time']) ? $shiftData['open_time'] : null;
                $closeTime = isset($shiftData['close_time']) ? $shiftData['close_time'] : null;
            }
        }

        $shiftBuilder->insert([
            'status'       => $status,
            'initial_cash' => $initialCash,
            'open_time'    => $openTime,
            'close_time'   => $closeTime,
        ]);
        echo "SHIFT_SEEDED: Successfully imported shift status.\n";
    }
}
