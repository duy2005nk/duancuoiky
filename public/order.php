<?php
declare(strict_types=1);

require_once __DIR__ . '/components/organisms/order_form.php';

$cssVersion = filemtime(__DIR__ . '/assets/order-screen.css') ?: time();

$products = $products ?? [];
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt hàng B2B - HKD Trịnh Văn Hùng</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/order-screen.css?v=<?= $cssVersion ?>">
</head>
<?php renderOrderFormScript($products); ?>
<body>
<main class="orders-page" x-data="orderPage()">
    <?php renderPageHeader(); ?>
    <section class="orders-content order-content">
                <div class="order-heading">
                    <div>
                        <nav class="breadcrumb" aria-label="Breadcrumb">Bán hàng <span>/</span> Phiếu đặt hàng mới</nav>
                        <h1>Đặt hàng thực phẩm</h1>
                        <p>Nhà ăn công ty chọn hàng, kiểm tra phiếu và gửi đơn trong ngày.</p>
                    </div>
                    <div class="delivery-status" aria-label="Thông tin thời gian chốt đơn">
                        <div>
                            <span class="status-dot" aria-hidden="true"></span>
                            <span>Nhận đơn hôm nay</span>
                        </div>
                        <strong>Trước 18:00</strong>
                    </div>
                </div>
                <div class="order-layout">
            <?php renderProductCatalog($products); ?>
            <?php renderOrderForm($products); ?>
                </div>
    </section>
</main>
    <script>
        window.ORDER_PRODUCTS = <?= json_encode($products, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    </script>
</body>
</html>
