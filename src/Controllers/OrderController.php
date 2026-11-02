<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use InvalidArgumentException;
use Throwable;

final class OrderController
{
    public function __construct(
        private OrderService $orderService,
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository
    )
    {
    }

    public function index(): void
    {
        $user = $_SESSION['user'];
        $orders = $this->orderRepository->findAll(
            $user['role'] === 'admin' ? null : (int) $user['company_id']
        );
        require dirname(__DIR__, 2) . '/public/orders.php';
    }

    public function show(int $orderId): void
    {
        $user = $_SESSION['user'];
        $order = $this->orderRepository->findById(
            $orderId,
            $user['role'] === 'admin' ? null : (int) $user['company_id']
        );
        if ($order === null) {
            http_response_code(404);
            exit('Không tìm thấy đơn hàng.');
        }
        require dirname(__DIR__, 2) . '/public/order_detail.php';
    }

    public function catalog(): void
    {
        $user = $_SESSION['user'];
        if ($user['role'] === 'admin') {
            header('Location: /orders');
            exit;
        }
        $catalogRows = $this->productRepository->findActive((int) $user['company_id']);
        $products = [];
        foreach ($catalogRows as $row) {
            $products[(int) $row['id']] = [
                'name' => $row['name'],
                'unit' => $row['unit'],
                'price' => (float) $row['price'],
            ];
        }
        require dirname(__DIR__, 2) . '/public/order.php';
    }

    public function updateStatus(int $orderId): void
    {
        if (($_SESSION['user']['role'] ?? null) !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Bạn không có quyền cập nhật trạng thái đơn hàng.']);
            exit;
        }
        $status = (string) ($_POST['status'] ?? '');
        if (!in_array($status, ['PENDING_APPROVAL', 'APPROVED', 'IN_TRANSIT', 'DELIVERED', 'REJECTED'], true)) {
            http_response_code(422);
            echo json_encode(['error' => 'Trạng thái không hợp lệ.']);
            exit;
        }
        $this->orderRepository->ensureStatusSchema();
        $this->orderRepository->updateStatus($orderId, $status);
        header('Location: /orders/' . $orderId);
        exit;
    }

    public function createRequest(array $user): void
    {
        header('Content-Type: application/json; charset=utf-8');
        if ($user['role'] === 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Quản trị viên không được đặt hàng thay nhà ăn.']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input') ?: '{}', true);
        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode(['error' => 'Request body must be valid JSON.']);
            exit;
        }
        $input['company_id'] = $user['company_id'];
        $this->create($input);
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
