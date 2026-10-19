<?php
declare(strict_types=1);

require_once __DIR__ . '/molecules.php';

function renderPageHeader(): void
{
    echo '<header class="border-b border-brand-800 bg-brand-700 text-white shadow-sm">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-5">
            <div><p class="text-xs font-semibold uppercase tracking-widest text-brand-100">B2B Foods</p><h1 class="text-xl font-bold sm:text-2xl">HKD Trịnh Văn Hùng</h1></div>
            <div class="rounded-lg bg-white/10 px-3 py-2 text-right"><p class="text-xs text-brand-100">Giờ chốt đơn</p><p class="font-semibold">18:00 hàng ngày</p></div>
        </div>
    </header>';
}

function renderProductCatalog(array $products): void
{
    echo '<section class="rounded-panel border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5"><h2 class="text-lg font-bold text-slate-900">Danh mục thực phẩm</h2><p class="mt-1 text-sm text-slate-500">Chọn sản phẩm và điều chỉnh số lượng cần đặt.</p></div>
        <div id="product-list" class="grid gap-3 sm:grid-cols-2">';
    foreach ($products as $productId => $product) {
        renderProductCard((int) $productId, $product);
    }
    echo '</div></section>';
}

function renderOrderForm(): void
{
    echo '<aside class="rounded-panel border border-slate-200 bg-white shadow-sm lg:sticky lg:top-6 lg:self-start">
        <form id="order-form" novalidate>
            <div class="border-b border-slate-200 p-5"><h2 class="text-lg font-bold text-slate-900">Thông tin đặt hàng</h2>
                <label for="company-id" class="mt-4 block text-sm font-medium text-slate-700">Nhà ăn / Công ty</label>
                <select id="company-id" name="company_id" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    <option value="1">Nhà ăn công ty 1</option><option value="2">Nhà ăn công ty 2</option>
                </select>
            </div>
            <div class="p-5"><div class="mb-3 flex items-center justify-between"><h3 class="font-semibold text-slate-900">Giỏ hàng</h3><button id="clear-cart" type="button" class="text-sm font-medium text-slate-500 hover:text-red-600">Xóa tất cả</button></div>
                <div id="cart-list" class="space-y-3"></div>
                <p id="empty-cart" class="rounded-lg bg-slate-50 py-8 text-center text-sm text-slate-500">Giỏ hàng đang trống</p>
            </div>';
    renderAlertSlot();
    echo '<div class="border-t border-slate-200 p-5"><button id="submit-order" type="submit" disabled class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-3 font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-300">';
    renderLoadingSpinner('loading-icon');
    echo '<span id="submit-label">Chốt Đơn</span></button></div></form></aside>';
}
