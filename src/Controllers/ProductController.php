<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProductService;

final class ProductController
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(): void
    {
        if (!$this->isAdmin()) {
            $this->forbidden();
        }
        $products = $this->productService->all();
        require dirname(__DIR__, 2) . '/public/admin_products.php';
    }

    public function save(): void
    {
        if (!$this->isAdmin()) {
            $this->forbidden();
        }

        try {
            $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $this->productService->save([
                'name' => trim((string) ($_POST['name'] ?? '')),
                'unit' => trim((string) ($_POST['unit'] ?? '')),
                'price' => $_POST['price'] ?? null,
                'active' => isset($_POST['active']) && !isset($_POST['inactive']),
            ], $id === false ? null : (int) $id);
            $this->redirect('/admin/products');
        } catch (\InvalidArgumentException $exception) {
            $_SESSION['admin_error'] = $exception->getMessage();
            $this->redirect('/admin/products');
        }
    }

    private function isAdmin(): bool
    {
        return ($_SESSION['user']['role'] ?? null) === 'admin';
    }

    private function forbidden(): void
    {
        http_response_code(403);
        exit('Bạn không có quyền truy cập khu vực quản trị.');
    }

    private function redirect(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
