<?php
declare(strict_types=1);

require_once __DIR__ . '/atoms.php';

function renderAlertSlot(): void
{
    echo '<div id="message" class="mx-5 mb-4 hidden rounded-lg p-3 text-sm" role="alert" aria-live="polite"></div>';
}

function renderProductCard(int $productId, array $product): void
{
    echo '<article class="flex items-center justify-between rounded-xl border border-slate-200 p-4">
        <div><p class="font-semibold text-slate-900">' . e($product['name']) . '</p>
        <p class="mt-1 text-xs text-slate-500">Mã sản phẩm ' . $productId . '</p></div>
        <button type="button" data-add-product="' . $productId . '" class="rounded-lg bg-brand-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">+ Thêm</button>
    </article>';
}

function renderQuantityControl(int|string $productId, array $product, float|int $quantity): void
{
    echo '<div class="flex items-center gap-2 rounded-lg border border-slate-200 p-3" data-cart-row="' . e((string) $productId) . '">';
    echo '<p class="min-w-0 flex-1 truncate text-sm font-semibold text-slate-800">' . e($product['name']) . '</p>';
    echo '<label class="sr-only" for="quantity-' . e((string) $productId) . '">' . e('Số lượng ' . $product['name']) . '</label>';
    echo '<input id="quantity-' . e((string) $productId) . '" data-cart-quantity="' . e((string) $productId) . '" type="number" min="0.1" step="0.1" inputmode="decimal" required value="' . e((string) $quantity) . '" class="w-20 rounded-md border border-slate-300 px-2 py-1.5 text-right text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">';
    echo '<span class="w-6 text-xs text-slate-500">' . e($product['unit']) . '</span>';
    echo '<button type="button" data-remove-product="' . e((string) $productId) . '" class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-red-600" aria-label="Xóa ' . e($product['name']) . '">×</button></div>';
}
