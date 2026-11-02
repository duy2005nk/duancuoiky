# PHP REST Backend Reference

## Recommended project layout

```text
public/
  index.php
config/
  database.php
src/
  Controllers/
  Services/
  Repositories/
  Domain/
database/
  migrations/
  seeds/
tests/
openapi.yaml
docker-compose.yml
composer.json
```

Adapt this layout to existing project conventions. Do not add framework folders when the project is intentionally lightweight.

## Native PHP request flow

1. Parse method and path once at the front controller.
2. Route GET and POST separately.
3. Parse JSON safely.
4. Build typed dependencies.
5. Call the controller.
6. Return a stable status and JSON payload.
7. Convert expected exceptions to responses.

Keep route dispatch from becoming a large controller that owns all repository and service construction. Use a dependency container or application factory as the project grows.

## PDO connection reference

Use environment variables and a configured DSN.

```php
$dsn = sprintf(
    'pgsql:host=%s;port=%s;dbname=%s',
    getenv('DB_HOST') ?: 'localhost',
    getenv('DB_PORT') ?: '5432',
    getenv('DB_NAME') ?: 'app',
);

$pdo = new PDO(
    $dsn,
    getenv('DB_USER') ?: 'postgres',
    getenv('DB_PASS') ?: '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
);
```

Use one connection ownership model consistently instead of opening connections throughout the request.

## Transaction template

```php
$this->db->beginTransaction();

try {
    // Execute prepared statements.
    $this->db->commit();
} catch (Throwable $exception) {
    if ($this->db->inTransaction()) {
        $this->db->rollBack();
    }

    throw $exception;
}
```

Do not catch and ignore exceptions only to roll back. Always return or rethrow the failure.

## Numeric precision

For monetary values:

- Store money in integer smallest units, or use the database decimal strategy already defined by the schema.
- Validate decimal quantities separately from product or unit identifiers.
- Round with an explicit documented policy.
- Recalculate totals on the server.
- Never trust `total_amount` or `applied_price` from request bodies.

## Repository test checklist

- Correct rows inserted.
- Returned identifiers are valid.
- All related rows are written.
- Transaction rolls back on statement failure.
- Reserved identifiers are handled safely.
- No client-supplied price is stored without validation.
- Query parameters cannot alter SQL structure.
