<?php
declare(strict_types=1);

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - HKD Trịnh Văn Hùng</title>
    <link rel="stylesheet" href="/assets/order-screen.css?v=<?= filemtime(__DIR__ . '/assets/order-screen.css') ?: time() ?>">
</head>
<body>
<main class="login-page">
    <form class="login-card" method="post" action="/login">
        <div class="brand-mark">TVH</div>
        <span class="eyebrow">HKD Trịnh Văn Hùng</span>
        <h1>Đăng nhập hệ thống</h1>
        <?php if ($error !== ''): ?>
            <div class="login-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <label for="username">Tên đăng nhập</label>
        <input id="username" name="username" autocomplete="username" required>
        <label for="password">Mật khẩu</label>
        <input id="password" name="password" type="password" autocomplete="current-password"
               pattern=".*@.*" title="Mật khẩu phải chứa ký tự @" required>
        <small>Mật khẩu demo phải chứa ký tự @.</small>
        <button class="submit-button" type="submit">Đăng nhập</button>
    </form>
</main>
</body>
</html>
