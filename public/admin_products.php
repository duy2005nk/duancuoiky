<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản phẩm - HKD Trịnh Văn Hùng</title>
    <link rel="stylesheet" href="/assets/order-screen.css?v=<?= filemtime(__DIR__ . '/assets/order-screen.css') ?: time() ?>">
</head>
<body>
<main class="orders-page">
    <header class="orders-header">
        <a class="brand" href="/admin"><span class="brand-mark">TVH</span><strong>HKD Trịnh Văn Hùng</strong></a>
        <nav><a href="/admin">Tổng quan</a><a href="/orders">Quản lý đơn</a><a class="active" href="/admin/products">Sản phẩm</a></nav>
        <div class="account-menu"><strong><?= htmlspecialchars((string) $_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?></strong>
            <form method="post" action="/logout"><button class="logout-button">Đăng xuất</button></form></div>
    </header>
    <section class="orders-content">
        <div class="order-heading"><div><span class="eyebrow">Danh mục kinh doanh</span><h1>Quản lý sản phẩm</h1></div></div>
        <form class="product-form" method="post" action="/admin/products/save">
            <input name="name" required maxlength="150" placeholder="Tên sản phẩm">
            <input name="unit" required maxlength="30" placeholder="Đơn vị">
            <input name="price" required type="number" min="0" step="0.01" placeholder="Giá cơ sở">
            <label><input name="active" type="checkbox" checked> Đang bán</label>
            <button class="submit-button" type="submit">Thêm sản phẩm</button>
        </form>
        <div class="orders-table-wrap">
            <table class="orders-table">
                <thead><tr><th>Sản phẩm</th><th>Đơn vị</th><th>Giá mới nhất</th><th>Trạng thái</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($product['unit'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format((float) $product['price'], 0, ',', '.') ?> đ</td>
                        <td><?= $product['active'] ? 'Đang bán' : 'Ngừng bán' ?></td>
                        <td>
                            <form class="inline-product-action" method="post" action="/admin/products/save">
                                <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="unit" value="<?= htmlspecialchars($product['unit'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="price" value="<?= (float) $product['price'] ?>">
                                <?php if ($product['active']): ?>
                                    <input type="hidden" name="inactive" value="1">
                                <?php else: ?>
                                    <input type="hidden" name="active" value="1">
                                <?php endif ?>
                                <button class="clear-button" type="submit"><?= $product['active'] ? 'Ngừng bán' : 'Mở bán' ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
