<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết đơn #<?= (int) $order['id'] ?> - HKD Trịnh Văn Hùng</title>
    <link rel="stylesheet" href="/assets/order-screen.css?v=<?= filemtime(__DIR__ . '/assets/order-screen.css') ?: time() ?>">
</head>
<body>
<main class="orders-page">
    <header class="orders-header">
        <a class="brand" href="/"><span class="brand-mark">TVH</span><strong>Trịnh Văn Hùng</strong></a>
        <nav>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="/admin">Tổng quan</a>
                <a class="active" href="/orders">Quản lý đơn</a>
                <a href="/admin/products">Sản phẩm</a>
            <?php endif ?>
            <?php if ($_SESSION['user']['role'] === 'customer'): ?>
                <a href="/">Đặt hàng</a>
                <a class="active" href="/orders">Đơn đã gửi</a>
            <?php endif ?>
        </nav>
        <div class="account-menu">
            <strong><?= htmlspecialchars((string) $_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?></strong>
            <form method="post" action="/logout"><button class="logout-button">Đăng xuất</button></form>
        </div>
    </header>
    <section class="orders-content">
        <a class="back-link" href="/orders">← Quay lại danh sách</a>
        <div class="detail-heading">
            <div><span class="eyebrow">Phiếu đặt hàng</span><h1>Đơn #<?= (int) $order['id'] ?></h1></div>
            <span class="order-status"><?= htmlspecialchars((string) $order['status'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div class="detail-grid">
            <div class="detail-card"><span>Nhà ăn</span><strong><?= htmlspecialchars($order['company_name'], ENT_QUOTES, 'UTF-8') ?></strong></div>
            <div class="detail-card"><span>Ngày nhận hàng</span><strong><?= htmlspecialchars(substr((string) $order['delivery_date'], 0, 10), ENT_QUOTES, 'UTF-8') ?></strong></div>
            <div class="detail-card"><span>Tổng tiền</span><strong><?= number_format((float) $order['total_amount'], 0, ',', '.') ?> đ</strong></div>
        </div>
        <div class="detail-items">
            <?php foreach ($order['items'] as $item): ?>
                <div><strong><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></strong><span><?= number_format((float) $item['quantity'], 1, ',', '.') ?> <?= htmlspecialchars($item['unit'], ENT_QUOTES, 'UTF-8') ?> × <?= number_format((float) $item['applied_price'], 0, ',', '.') ?> đ</span><b><?= number_format((float) $item['line_total'], 0, ',', '.') ?> đ</b></div>
            <?php endforeach ?>
        </div>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <form class="status-actions" method="post" action="/api/v1/orders/<?= (int) $order['id'] ?>/status">
                <label for="status">Quản lý trạng thái</label>
                <select id="status" name="status">
                    <?php foreach (['PENDING_APPROVAL', 'APPROVED', 'IN_TRANSIT', 'DELIVERED', 'REJECTED'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $order['status'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach ?>
                </select>
                <button class="submit-button" type="submit">Cập nhật trạng thái</button>
            </form>
        <?php endif ?>
    </section>
</main>
</body>
</html>
