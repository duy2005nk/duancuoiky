<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\OrderRepository;

final class AdminController
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    public function dashboard(): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            exit('Bạn không có quyền truy cập khu vực quản trị.');
        }
        $stats = $this->orderRepository->dashboardStats();
        require dirname(__DIR__, 2) . '/public/admin_dashboard.php';
    }

    private function isAdmin(): bool
    {
        return ($_SESSION['user']['role'] ?? null) === 'admin';
    }
}
