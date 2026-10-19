<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\OrderService;
use InvalidArgumentException;
use Throwable;

final class OrderController
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function create(array $input): void
    {
        try {
            $companyId = filter_var($input['company_id'] ?? null, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
            ]);
            if ($companyId === false) {
                throw new InvalidArgumentException('company_id is required and must be positive.');
            }

            $items = $input['items'] ?? [];
            if (!is_array($items)) {
                throw new InvalidArgumentException('items must be an array.');
            }

            $result = $this->orderService->createOrder((int) $companyId, array_values($items));
            $httpStatus = $result['status'] === OrderService::PENDING_APPROVAL ? 202 : 201;
            $isPending = $httpStatus === 202;
            $this->json($httpStatus, [
                'success' => true,
                'message' => $isPending
                    ? 'Đơn hàng đã được ghi nhận và đang chờ duyệt do đặt sau 18:00.'
                    : 'Đơn hàng đã được tạo thành công.',
                'order_id' => (int) $result['id'],
                'status' => $result['status'],
                'total_amount' => $result['total_amount'],
            ]);
        } catch (InvalidArgumentException $exception) {
            $this->json(422, ['error' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            $this->json(500, ['error' => 'Order could not be created.', 'detail' => $exception->getMessage()]);
        }
    }

    private function json(int $status, array $payload): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}
