<?php

declare(strict_types=1);

namespace App;

use App\Config\Database;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Repositories\AuthRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PriceRepository;
use App\Repositories\ProductRepository;
use App\Services\AuthService;
use App\Services\OrderService;
use App\Services\ProductService;

final class Application
{
    public function handle(string $method, string $path): void
    {
        $this->serveAsset($method, $path);

        $db = Database::connection();
        (new AuthRepository($db))->ensureSchema();
        (new ProductRepository($db))->ensureSchema();

        $GLOBALS['current_user'] = $_SESSION['user'] ?? null;
        $currentUser = $GLOBALS['current_user'];

        $authRepository = new AuthRepository($db);
        $authController = new AuthController(new AuthService($authRepository));
        $orderRepository = new OrderRepository($db);
        $productRepository = new ProductRepository($db);
        $orderController = new OrderController(
            new OrderService($orderRepository, new PriceRepository($db)),
            $orderRepository,
            $productRepository
        );
        $productController = new ProductController(new ProductService($productRepository));
        $adminController = new AdminController($orderRepository);

        if ($method === 'GET' && $path === '/login') {
            $authController->loginForm();
            return;
        }
        if ($method === 'POST' && $path === '/login') {
            $authController->login();
            return;
        }
        if ($method === 'POST' && $path === '/logout') {
            $authController->logout();
            return;
        }
        if ($currentUser === null) {
            header('Location: /login');
            exit;
        }

        if ($currentUser['role'] === 'customer' && str_starts_with($path, '/admin')) {
            http_response_code(403);
            exit('Bạn không có quyền truy cập khu vực quản trị.');
        }

        if ($method === 'GET' && $path === '/admin') {
            $adminController->dashboard();
            return;
        }
        if ($method === 'GET' && $path === '/admin/products') {
            $productController->index();
            return;
        }
        if ($method === 'POST' && $path === '/admin/products/save') {
            $productController->save();
            return;
        }
        if ($method === 'GET' && $path === '/orders') {
            $orderController->index();
            return;
        }
        if ($method === 'GET' && preg_match('#^/orders/([1-9]\d*)$#', $path, $matches)) {
            $orderController->show((int) $matches[1]);
            return;
        }
        if ($method === 'POST' && preg_match('#^/api/v1/orders/([1-9]\d*)/status$#', $path, $matches)) {
            $orderController->updateStatus((int) $matches[1]);
            return;
        }
        if ($method === 'GET' && ($path === '/' || $path === '/order')) {
            $orderController->catalog();
            return;
        }
        if ($method === 'POST' && $path === '/api/v1/orders') {
            $orderController->createRequest($currentUser);
            return;
        }

        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found.'], JSON_UNESCAPED_UNICODE);
    }

    private function serveAsset(string $method, string $path): void
    {
        if ($method !== 'GET' || !str_starts_with($path, '/assets/')) {
            return;
        }
        $publicDir = dirname(__DIR__) . '/public';
        $assetPath = realpath($publicDir . $path);
        if ($assetPath === false || !str_starts_with($assetPath, realpath($publicDir) . DIRECTORY_SEPARATOR)) {
            return;
        }
        $types = [
            'css' => 'text/css; charset=utf-8',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
        ];
        $extension = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));
        if (!isset($types[$extension])) {
            return;
        }
        header('Content-Type: ' . $types[$extension]);
        header('Cache-Control: no-cache, must-revalidate');
        readfile($assetPath);
        exit;
    }
}
