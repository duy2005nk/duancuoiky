<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class AuthRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function ensureSchema(): void
    {
        $this->db->exec('CREATE TABLE IF NOT EXISTS app_user (
            id SERIAL PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL CHECK (role IN (\'admin\', \'customer\')),
            company_id INTEGER REFERENCES company(id)
        )');

        $this->upsert('admin', password_hash('admin@123', PASSWORD_DEFAULT), 'admin', null);
        $this->upsert('customer', password_hash('customer@123', PASSWORD_DEFAULT), 'customer', 1);
    }

    public function findByUsername(string $username): ?array
    {
        $statement = $this->db->prepare('SELECT id, username, password_hash, role, company_id FROM app_user WHERE username = :username LIMIT 1');
        $statement->execute(['username' => $username]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user === false ? null : $user;
    }

    private function upsert(string $username, string $passwordHash, string $role, ?int $companyId): void
    {
        $statement = $this->db->prepare(
            'INSERT INTO app_user (username, password_hash, role, company_id)
             VALUES (:username, :password_hash, :role, :company_id)
             ON CONFLICT (username) DO UPDATE SET password_hash = EXCLUDED.password_hash, role = EXCLUDED.role, company_id = EXCLUDED.company_id'
        );
        $statement->execute([
            'username' => $username,
            'password_hash' => $passwordHash,
            'role' => $role,
            'company_id' => $companyId,
        ]);
    }
}
