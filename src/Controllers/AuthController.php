<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use InvalidArgumentException;

final class AuthController
{
    public function __construct(private AuthService $authService)
    {
    }

    public function loginForm(): void
    {
        if (($GLOBALS['current_user'] ?? null) !== null) {
            $this->redirect($GLOBALS['current_user']['role'] === 'admin' ? '/admin' : '/');
        }
        require dirname(__DIR__, 2) . '/public/login.php';
    }

    public function login(): void
    {
        try {
            $user = $this->authService->login(
                (string) ($_POST['username'] ?? ''),
                (string) ($_POST['password'] ?? '')
            );
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $this->redirect($user['role'] === 'admin' ? '/admin' : '/');
        } catch (InvalidArgumentException $exception) {
            $_SESSION['login_error'] = $exception->getMessage();
            $this->redirect('/login');
        }
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }

    private function redirect(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
