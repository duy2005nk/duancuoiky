<?php

declare(strict_types=1);

use App\Config\Database;
use App\Controllers\OrderController;
use App\Repositories\OrderRepository;
use App\Repositories\PriceRepository;
use App\Services\OrderService;

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($method === 'GET' && ($path === '/' || $path === '/orders')) {
    require __DIR__ . '/order.php';
    exit;
}

if ($method === 'POST' && $path === '/api/v1/orders') {
    header('Content-Type: application/json; charset=utf-8');
    require_once dirname(__DIR__) . '/config/database.php';
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    $rawBody = file_get_contents('php://input');
    $input = json_decode($rawBody ?: '{}', true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Request body must be valid JSON.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $db = Database::connection();
    $controller = new OrderController(
        new OrderService(
            new OrderRepository($db),
            new PriceRepository($db)
        )
    );
    $controller->create($input);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Endpoint not found.'], JSON_UNESCAPED_UNICODE);
