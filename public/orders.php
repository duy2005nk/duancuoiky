<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đơn đã gửi - HKD Trịnh Văn Hùng</title>
    <link rel="stylesheet" href="/assets/order-screen.css?v=<?= filemtime(__DIR__ . '/assets/order-screen.css') ?: time() ?>">
</head>
<body>
<main class="orders-page">
    <header class="orders-header">
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a class="brand" href="/admin"><span class="brand-mark">TVH</span><strong>HKD Trịnh Văn Hùng</strong></a>
            <nav><a href="/admin">Tổng quan</a><a class="active" href="/orders">Quản lý đơn</a><a href="/admin/products">Sản phẩm</a></nav>
        <?php else: ?>
            <a class="brand" href="/"><span class="brand-mark">TVH</span><span><strong>Trịnh Văn Hùng</strong><small>Thực phẩm B2B</small></span></a>
            <nav><a href="/">Đặt hàng</a><a class="active" href="/orders">Đơn đã gửi</a></nav>
        <?php endif ?>
        <div class="account-menu"><strong><?= htmlspecialchars((string) $_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?></strong>
            <form method="post" action="/logout"><button class="logout-button">Đăng xuất</button></form></div>
    </header>
    <section class="orders-content">
        <div class="order-heading">
            <div><span class="eyebrow">Theo dõi đơn hàng</span><h1>Đơn đã gửi</h1></div>
        </div>
        <?php if ($orders === []): ?>
            <div class="empty-orders">Chưa có đơn hàng nào.</div>
        <?php else: ?>
            <div class="orders-table-wrap">
                <table class="orders-table">
                    <thead><tr><th>Mã đơn</th><th>Nhà ăn</th><th>Ngày nhận hàng</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= (int) $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['company_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(substr((string) $order['delivery_date'], 0, 10), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float) $order['total_amount'], 0, ',', '.') ?> đ</td>
                            <td><span class="order-status status-<?= htmlspecialchars(strtolower((string) $order['status']), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $order['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                            <td><a href="/orders/<?= (int) $order['id'] ?>">Chi tiết</a></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </section>
</main>
</body>
</html>
