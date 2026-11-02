<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản trị - HKD Trịnh Văn Hùng</title>
    <link rel="stylesheet" href="/assets/order-screen.css?v=<?= filemtime(__DIR__ . '/assets/order-screen.css') ?: time() ?>">
</head>
<body>
<main class="orders-page">
    <header class="orders-header">
        <a class="brand" href="/admin"><span class="brand-mark">TVH</span><strong>HKD Trịnh Văn Hùng</strong></a>
        <nav><a class="active" href="/admin">Tổng quan</a><a href="/orders">Quản lý đơn</a><a href="/admin/products">Sản phẩm</a></nav>
        <div class="account-menu"><strong><?= htmlspecialchars((string) $_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?></strong>
            <form method="post" action="/logout"><button class="logout-button">Đăng xuất</button></form></div>
    </header>
    <section class="orders-content">
        <div class="order-heading"><div><span class="eyebrow">Trung tâm quản trị</span><h1>Tổng quan hôm nay</h1></div></div>
        <div class="admin-stats">
            <article><span>Tổng đơn hàng</span><strong><?= number_format((int) $stats['total_orders']) ?></strong></article>
            <article><span>Đơn đang xử lý</span><strong><?= number_format((int) $stats['in_progress']) ?></strong></article>
            <article><span>Doanh thu</span><strong><?= number_format((float) $stats['revenue'], 0, ',', '.') ?> đ</strong></article>
        </div>
    </section>
</main>
</body>
</html>
