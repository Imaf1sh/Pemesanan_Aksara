<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
    public function up()
    {
        // 1. PRODUCTS TABLE
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'price' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'img' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('products', true);

        // 2. ORDERS TABLE
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'Guest',
            ],
            'total' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Cash',
            ],
            'order_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Dine In',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'pending',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('orders', true);

        // 3. ORDER ITEMS TABLE
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'price' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items', true);

        // 4. SHIFTS TABLE
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'closed',
            ],
            'initial_cash' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'open_time' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'close_time' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('shifts', true);
    }

    public function down()
    {
        $this->forge->dropTable('order_items', true);
        $this->forge->dropTable('orders', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('shifts', true);
    }
}
