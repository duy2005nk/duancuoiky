<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class OrderRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function saveOrder(array $orderData): array
    {
        $driver = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $quote = static fn(string $identifier): string => $driver === 'mysql'
            ? '`' . str_replace('`', '``', $identifier) . '`'
            : '"' . str_replace('"', '""', $identifier) . '"';
        $orderTable = $quote('order');

        $this->db->beginTransaction();
        try {
            $orderSql = "INSERT INTO {$orderTable}
                (company_id, status, total_amount, created_at, delivery_date)
                VALUES (:company_id, :status, :total_amount, :created_at, :delivery_date)";

            if ($driver === 'pgsql') {
                $orderSql .= ' RETURNING id';
            }

            $statement = $this->db->prepare($orderSql);
            $statement->execute([
                'company_id' => $orderData['company_id'],
                'status' => $orderData['status'],
                'total_amount' => $orderData['total_amount'],
                'created_at' => $orderData['created_at'],
                'delivery_date' => $orderData['delivery_date'],
            ]);

            $orderId = $driver === 'pgsql'
                ? (int) $statement->fetchColumn()
                : (int) $this->db->lastInsertId();

            $detailSql = "INSERT INTO {$quote('order_detail')}
                (order_id, product_id, quantity, applied_price)
                VALUES (:order_id, :product_id, :quantity, :applied_price)";
            $detailStatement = $this->db->prepare($detailSql);

            foreach ($orderData['items'] as $item) {
                $detailStatement->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'applied_price' => $item['applied_price'],
                ]);
            }

            $this->db->commit();
        } catch (\Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }

        return ['id' => $orderId];
    }
}
