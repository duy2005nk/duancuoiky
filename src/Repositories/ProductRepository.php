<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ProductRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function ensureSchema(): void
    {
        $this->db->exec('ALTER TABLE product ADD COLUMN IF NOT EXISTS active BOOLEAN NOT NULL DEFAULT TRUE');
    }

    public function findActive(int $companyId): array
    {
        $statement = $this->db->prepare(
            'SELECT p.id, p.name, p.unit, COALESCE(
                (SELECT pl.price FROM price_list pl
                 WHERE pl.product_id = p.id AND pl.company_id = :company_id
                   AND pl.valid_from <= CURRENT_DATE
                 ORDER BY pl.valid_from DESC LIMIT 1), 0
             ) AS price
             FROM product p WHERE p.active = TRUE ORDER BY p.id'
        );
        $statement->execute(['company_id' => $companyId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll(): array
    {
        return $this->db->query(
            'SELECT p.id, p.name, p.unit, p.active,
                    COALESCE((
                        SELECT pl.price FROM price_list pl
                        WHERE pl.product_id = p.id
                        ORDER BY pl.valid_from DESC LIMIT 1
                    ), 0) AS price
             FROM product p ORDER BY p.id'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $input, ?int $id = null): int
    {
        if ($id === null) {
            $statement = $this->db->prepare('INSERT INTO product (name, unit, active) VALUES (:name, :unit, :active) RETURNING id');
        } else {
            $statement = $this->db->prepare('UPDATE product SET name = :name, unit = :unit, active = :active WHERE id = :id');
        }
        $params = [
            'name' => trim((string) $input['name']),
            'unit' => trim((string) $input['unit']),
            'active' => isset($input['active']) ? 'true' : 'false',
        ];
        if ($id !== null) {
            $params['id'] = $id;
        }
        $statement->execute($params);
        if ($id === null) {
            $id = (int) $statement->fetchColumn();
        }
        return $id;
    }

    public function savePrice(int $productId, float $price): void
    {
        $companies = $this->db->query('SELECT id FROM company')->fetchAll(PDO::FETCH_COLUMN);
        $statement = $this->db->prepare(
            'INSERT INTO price_list (company_id, product_id, price, valid_from)
             VALUES (:company_id, :product_id, :price, CURRENT_DATE)
             ON CONFLICT (company_id, product_id, valid_from)
             DO UPDATE SET price = EXCLUDED.price'
        );
        foreach ($companies as $companyId) {
            $statement->execute([
                'company_id' => (int) $companyId,
                'product_id' => $productId,
                'price' => $price,
            ]);
        }
    }
}
