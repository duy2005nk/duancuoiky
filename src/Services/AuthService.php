<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthRepository;
use InvalidArgumentException;

final class AuthService
{
    public function __construct(private AuthRepository $repository)
    {
    }

    public function login(string $username, string $password): array
    {
        $username = trim($username);
        if ($username === '') {
            throw new InvalidArgumentException('Vui lòng nhập tên đăng nhập.');
        }
        if (!str_contains($password, '@')) {
            throw new InvalidArgumentException('Mật khẩu phải chứa ký tự @.');
        }

        $user = $this->repository->findByUsername($username);
        if ($user === null || !password_verify($password, $user['password_hash'])) {
            throw new InvalidArgumentException('Tên đăng nhập hoặc mật khẩu không đúng.');
        }

        return [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'company_id' => $user['company_id'] === null ? null : (int) $user['company_id'],
        ];
    }
}
