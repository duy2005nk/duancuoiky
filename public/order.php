<?php
declare(strict_types=1);

require_once __DIR__ . '/components/organisms/order_form.php';

$products = [
    1 => ['name' => 'Đậu phụ', 'unit' => 'Bìa'],
    2 => ['name' => 'Rau củ tổng hợp', 'unit' => 'kg'],
    3 => ['name' => 'Rau sạch theo mùa', 'unit' => 'kg'],
];
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt hàng B2B - HKD Trịnh Văn Hùng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46' },
                        warning: { 50: '#fffbeb', 200: '#fde68a', 800: '#92400e' }
                    },
                    borderRadius: { panel: '1rem' },
                    fontFamily: {
                        display: ['Fraunces', 'serif'],
                        sans: ['Manrope', 'sans-serif']
                    }
                }
            }
        };
    </script>
</head>
<?php renderOrderFormScript($products); ?>
<body class="min-h-screen bg-[#f4f1e8] font-sans text-[#1d2923]">
    <?php renderPageHeader(); ?>
    <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-9">
        <div x-data="orderPage()" class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
            <?php renderProductCatalog($products); ?>
            <?php renderOrderForm($products); ?>
        </div>
    </main>
    <script>
        window.ORDER_PRODUCTS = <?= json_encode($products, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    </script>
</body>
</html>
