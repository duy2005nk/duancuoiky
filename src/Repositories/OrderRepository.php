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

    public function findAll(?int $companyId = null): array
    {
        $sql = 'SELECT o.id, o.company_id, c.name AS company_name, o.status, o.total_amount,
                       o.created_at, o.delivery_date
                FROM "order" o
                JOIN company c ON c.id = o.company_id';
        $params = [];
        if ($companyId !== null) {
            $sql .= ' WHERE o.company_id = :company_id';
            $params['company_id'] = $companyId;
        }
        $sql .= ' ORDER BY o.created_at DESC, o.id DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function findById(int $orderId, ?int $companyId = null): ?array
    {
        $sql = 'SELECT o.id, o.company_id, c.name AS company_name, o.status, o.total_amount,
                       o.created_at, o.delivery_date
                FROM "order" o
                JOIN company c ON c.id = o.company_id
                WHERE o.id = :order_id';
        $params = ['order_id' => $orderId];
        if ($companyId !== null) {
            $sql .= ' AND o.company_id = :company_id';
            $params['company_id'] = $companyId;
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        $order = $statement->fetch();
        if ($order === false) {
            return null;
        }

        $detailStatement = $this->db->prepare(
            'SELECT od.product_id, p.name, p.unit, od.quantity, od.applied_price,
                    od.quantity * od.applied_price AS line_total
             FROM order_detail od
             JOIN product p ON p.id = od.product_id
             WHERE od.order_id = :order_id'
        );
        $detailStatement->execute(['order_id' => $orderId]);
        $order['items'] = $detailStatement->fetchAll();
        return $order;
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $statement = $this->db->prepare('UPDATE "order" SET status = :status WHERE id = :id');
        $statement->execute(['status' => $status, 'id' => $orderId]);
        return $statement->rowCount() === 1;
    }

    public function ensureStatusSchema(): void
    {
        $this->db->exec(
            'ALTER TABLE "order" DROP CONSTRAINT IF EXISTS order_status_check;
             ALTER TABLE "order" ADD CONSTRAINT order_status_check CHECK (
                 status IN (\'PENDING_APPROVAL\', \'APPROVED\', \'IN_TRANSIT\', \'DELIVERED\', \'REJECTED\')
             );'
        );
    }

    public function dashboardStats(): array
    {
        $row = $this->db->query(
            'SELECT COUNT(*)::int AS total_orders,
                    COALESCE(SUM(total_amount), 0)::numeric AS revenue
             FROM "order" WHERE status <> \'REJECTED\''
        )->fetch();
        $pending = (int) $this->db->query(
            "SELECT COUNT(*) FROM \"order\" WHERE status IN ('PENDING_APPROVAL', 'APPROVED', 'IN_TRANSIT')"
        )->fetchColumn();
        return [
            'total_orders' => (int) $row['total_orders'],
            'revenue' => (float) $row['revenue'],
            'in_progress' => $pending,
        ];
    }
}
