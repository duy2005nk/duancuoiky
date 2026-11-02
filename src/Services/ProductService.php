<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductRepository;
use InvalidArgumentException;

final class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function all(): array
    {
        return $this->repository->findAll();
    }

    public function save(array $input, ?int $id = null): int
    {
        $price = filter_var($input['price'], FILTER_VALIDATE_FLOAT);
        if ($input['name'] === '' || $input['unit'] === '' || $price === false || $price < 0) {
            throw new InvalidArgumentException('Tên, đơn vị và giá sản phẩm không hợp lệ.');
        }

        $productId = $this->repository->save([
            'name' => $input['name'],
            'unit' => $input['unit'],
            'active' => $input['active'] ? 1 : null,
        ], $id);
        $this->repository->savePrice($productId, (float) $price);
        return $productId;
    }
}
