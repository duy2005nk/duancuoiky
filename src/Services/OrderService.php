<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\PriceRepository;
use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;

final class OrderService
{
    public const APPROVED = 'APPROVED';
    public const PENDING_APPROVAL = 'PENDING_APPROVAL';

    public function __construct(
        private OrderRepository $orderRepository,
        private PriceRepository $priceRepository
    ) {
    }

    public function createOrder(int $companyId, array $items, ?DateTimeImmutable $now = null): array
    {
        if ($companyId <= 0) {
            throw new InvalidArgumentException('company_id is required and must be positive.');
        }
        if ($items === []) {
            throw new InvalidArgumentException('items must contain at least one item.');
        }

        $now ??= new DateTimeImmutable('now');
        $pricedItems = [];
        $total = 0.0;

        foreach ($items as $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                throw new InvalidArgumentException('Each item requires product_id and quantity.');
            }

            $productId = filter_var($item['product_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $quantity = filter_var($item['quantity'], FILTER_VALIDATE_FLOAT);
            if ($productId === false || $quantity === false || $quantity <= 0) {
                throw new InvalidArgumentException('product_id and quantity must be positive numbers.');
            }

            $appliedPrice = $this->priceRepository->getPrice($companyId, (int) $productId, $now);
            $lineTotal = round((float) $quantity * $appliedPrice, 2);
            $total = round($total + $lineTotal, 2);
            $pricedItems[] = [
                'product_id' => (int) $productId,
                'quantity' => (float) $quantity,
                'applied_price' => $appliedPrice,
            ];
        }

        $status = $now->format('H:i:s') < '18:00:00'
            ? self::APPROVED
            : self::PENDING_APPROVAL;

        $deliveryDate = $status === self::APPROVED
            ? $now->modify('+1 day')->format('Y-m-d')
            : $now->format('Y-m-d');

        $savedOrder = $this->orderRepository->saveOrder([
            'company_id' => $companyId,
            'status' => $status,
            'total_amount' => $total,
            'created_at' => $now->format('Y-m-d H:i:s'),
            'delivery_date' => $deliveryDate,
            'items' => $pricedItems,
        ]);

        return $savedOrder + [
            'status' => $status,
            'total_amount' => $total,
        ];
    }
}
