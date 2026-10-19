<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;
use RuntimeException;

final class PriceRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function getPrice(int $companyId, int $productId, \DateTimeInterface $at): float
    {
        $driver = $dbDriver = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $dateExpression = $driver === 'pgsql'
            ? 'valid_from::date'
            : 'DATE(valid_from)';

        $sql = "SELECT price
                FROM price_list
                WHERE company_id = :company_id
                  AND product_id = :product_id
                  AND {$dateExpression} <= :priced_at
                ORDER BY valid_from DESC
                LIMIT 1";

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'company_id' => $companyId,
            'product_id' => $productId,
            'priced_at' => $at->format('Y-m-d'),
        ]);

        $price = $statement->fetchColumn();
        if ($price === false) {
            throw new RuntimeException("No price found for company {$companyId} and product {$productId}.");
        }

        return round((float) $price, 2);
    }
}
