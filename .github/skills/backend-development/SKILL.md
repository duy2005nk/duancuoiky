---
name: backend-development
description: "Use when designing, implementing, reviewing, debugging, testing, documenting, or hardening PHP backend services, REST APIs, repositories, business rules, database migrations, authentication, transactions, Docker configuration, or backend integration across projects."
argument-hint: "Describe the backend feature, bug, review, endpoint, or architecture task"
---

# Backend Development

## Mission

Build backend software that is correct, secure, maintainable, observable, and easy to change. Follow the conventions of the current project first. Prefer explicit data flow, small dependencies, predictable errors, and server-owned business rules over framework-specific shortcuts.

This skill applies to PHP by default and can adapt the same engineering process to Node.js, Python, Java, Go, .NET, or another backend stack.

## Start with repository discovery

Before editing, inspect:

- Runtime, dependency manifest, framework, PHP version, and package scripts.
- Application entry points and route registration.
- Existing controllers or handlers, services, repositories, domain models, and database access.
- Configuration, environment variables, migrations, seed data, and container setup.
- Existing tests, linting, static analysis, formatting, and CI commands.
- API specifications, business requirements, and architecture documentation.

State the relevant stack and conventions discovered before proposing an implementation. Do not import an architecture from another project when the repository already defines one.

For this project, preserve the established flow:

```text
HTTP entry point
  -> Controller
    -> Service
      -> Repository
        -> PDO/PostgreSQL
```

Keep HTTP concerns in the entry point or controller. Keep use cases and business rules in services. Keep persistence details in repositories. Keep database connections centralized.

## Clarify scope before implementation

Identify:

- Expected behavior and business rules.
- Public API shape and HTTP methods.
- Required authorization and ownership constraints.
- Inputs, validation rules, defaults, and boundaries.
- Database schema and migration implications.
- Success, empty, validation, authorization, conflict, and failure states.
- Backward compatibility and rollout constraints.

Do not guess a business rule that materially changes behavior. If the repository does not contain enough information, document the assumption and choose the smallest reversible implementation.

## Implement feature slices vertically

Complete one feature from transport to persistence in a coherent vertical slice.

Typical PHP flow:

1. Register or locate the route in [public/index.php](/c:/duan-xdptpm-php/public/index.php).
2. Validate and translate request data at the controller boundary.
3. Execute use-case logic in the service.
4. Read or write data through repositories.
5. Persist related changes atomically.
6. Convert domain or validation failures into stable API responses.
7. Update API documentation and tests.

Use dependency injection through constructors. Prefer narrow interfaces for meaningful substitutions such as tests, external services, or alternate implementations. Avoid introducing interfaces that merely duplicate one concrete class.

## PHP conventions for this repository

- Use PHP 8 typed properties and strict types for files where the project already uses `declare(strict_types=1)`.
- Use namespaces and PSR-4 paths aligned with `composer.json`.
- Use `InvalidArgumentException` for invalid caller data.
- Use `DomainException` or a project-specific exception for invalid business state.
- Use `RuntimeException` for infrastructure or unavailable-resource failures.
- Use prepared statements and parameterized PDO values.
- Keep catch blocks narrow and preserve or translate exceptions deliberately.
- Return response payloads through the controller instead of mixing HTTP output throughout services.
- Include the autoloader and dependencies in the application entry point only where appropriate.

Example data flow:

```text
Controller validates company_id/items
  -> OrderService validates business rules and prices server-side
    -> OrderRepository saves order and details transactionally
      -> Controller returns success or structured error
```

## Controller and API rules

- Validate required fields, types, positive ranges, arrays, enums, and allowed lengths.
- Validate JSON at the transport boundary and reject malformed or non-object JSON consistently.
- Use appropriate status codes:
  - `200` for successful reads or updates.
  - `201` for created resources.
  - `202` when work has been accepted asynchronously or is awaiting approval.
  - `400` for malformed JSON or request syntax.
  - `401` for missing authentication.
  - `403` for authenticated but unauthorized operations.
  - `404` for missing resources.
  - `409` for conflicts.
  - `422` for valid syntax with invalid business input.
  - `5xx` for unexpected server failures.
- Keep response keys stable and document them.
- Do not expose exception messages, SQL statements, stack traces, file paths, or infrastructure details to clients unless the project explicitly requires it.
- Centralize JSON headers and error serialization when multiple endpoints share the same behavior.

## Service and domain rules

- Put state transitions, cut-off rules, calculations, authorization policy, and validation that depends on multiple fields in services or domain classes.
- Never trust authoritative values supplied by a client.
- Calculate totals and prices from server-side data.
- Capture the server time consistently and pass it into methods for deterministic testing.
- Use integer cents or a decimal strategy appropriate to currency precision.
- Round only at the documented calculation boundary.
- Preserve all unit, timezone, and business-rule assumptions.

If a service depends on the current time, allow a clock or optional timestamp input when the existing architecture permits it:

```php
public function createOrder(int $companyId, array $items, ?DateTimeImmutable $now = null): array
```

## Repository and database rules

- Use named prepared placeholders or repository-specific query builders.
- Quote reserved identifiers only through a verified database-specific helper.
- Use transactions for related writes that must succeed together.
- Roll back failed transactions without swallowing the original exception.
- Keep schema ownership in SQL migrations or migration files.
- Use foreign keys for essential relationships when supported.
- Add indexes for actual query predicates, joins, sorting, and constraints.
- Avoid N+1 queries and unbounded list endpoints.
- Apply pagination to collections.
- Use database constraints together with application validation, not as a replacement for it.
- Use optimistic or pessimistic locking when concurrent updates can overwrite business state.
- Keep tenant, company, region, and ownership filters in every query that handles protected resources.

Database changes should have:

- A forward migration.
- Appropriate constraints and indexes.
- Safe handling of nullable legacy data.
- Updated seed data when required.
- Verified rollback or compatibility behavior for risky production changes.

## Security baseline

Before completing backend work, check for:

- SQL injection and unsafe dynamic SQL.
- HTML, shell, command, template, path, and code injection sinks.
- Missing authentication or authorization.
- Object-level authorization and cross-tenant data access.
- Client-controlled prices, totals, status, ownership, roles, or hidden identifiers.
- Unsafe deserialization.
- CSRF protections for browser-based state-changing requests.
- Timing attacks and enumeration where relevant.
- Secrets committed in source or container files.
- Excessive logging, stack traces, or sensitive request bodies.
- Missing rate limiting for authentication or expensive endpoints.
- Open redirects, SSRF, unrestricted file uploads, and unsafe external URLs.
- Unbounded payload size, pagination, query depth, or expensive calculations.

Never weaken security to make a failing test pass. For a vulnerability issue, follow the repository-standard security workflow and report exact file, line, impact, and confidence.

## Authentication and authorization

- Authenticate protected operations before executing them.
- Authorize the specific resource and action, not only the route.
- Scope every repository query by authenticated identity or tenant when required.
- Keep authorization in a reusable policy, middleware, service, or repository boundary.
- Use constant-time comparisons for verification secrets where the platform supports it.
- Use the platform's secure password hashing and verification APIs.
- Rotate and expire credentials according to the application's policy.
- Store tokens, salts, database passwords, and signing keys outside source control.

## Configuration and Docker

- Read configuration from environment variables.
- Keep a safe default only when it is appropriate for local development.
- Provide `.env.example` with variable names, types, and non-secret sample values.
- Never commit real `.env`, credentials, private keys, or tokens.
- Keep application and dependency containers reproducible.
- Initialize databases and migrations through explicit, repeatable commands.
- Avoid rebuilding images for source changes when bind-mounted development volumes are available.
- Verify container health, port mappings, volumes, and dependency startup order.
- Add timeouts, bounded retries, and health checks for service dependencies when relevant.

## Testing strategy

Test behavior from the smallest layer that can detect the defect, then add integration coverage at the boundary.

Prioritize:

1. Unit tests for business rules and domain transitions.
2. Repository tests for persistence and transaction behavior.
3. Controller or HTTP tests for validation and response contracts.
4. Integration tests for database and external dependencies.
5. Contract tests for third-party APIs when the project has them.

Cover:

- Happy path.
- Missing and malformed fields.
- Type and range boundaries.
- Empty and duplicate collections.
- Unauthorized and forbidden operations.
- Missing resources.
- Conflicting state transitions.
- Transaction rollback.
- External-service timeout, unavailable service, and invalid response.
- Currency, quantity, timezone, cut-off time, and date boundaries.

Use deterministic timestamps and seeded identifiers. Keep tests independent, repeatable, and isolated by database transaction or fixture cleanup.

## Error handling

- Use domain-specific exceptions for expected business failures.
- Convert validation failures to client-facing error codes or stable messages.
- Convert infrastructure failures to sanitized generic responses.
- Log unexpected server errors with request context but without secrets.
- Log enough context to investigate: operation, resource identifiers, error category, duration, and request identifier.
- Do not log raw authorization headers, cookies, passwords, tokens, credit card data, or personal secrets.
- Preserve the original exception when wrapping it for debugging.

## API documentation

Update the appropriate specification after changing or adding endpoints:

- Request headers, method, and route.
- Path and query parameters.
- JSON body schema and required fields.
- Success payload and status codes.
- Validation and authorization error codes.
- Pagination, filtering, ordering, and idempotency.
- OpenAPI examples and schema names used by the repository.

Keep documentation synchronized with runtime behavior. Never document parameters that are not implemented.

## Debugging procedure

1. Reproduce the problem with the narrowest command, request, or test.
2. Inspect the route, controller, service, repository, schema, and configuration involved.
3. Add temporary evidence at the smallest useful boundary.
4. Identify the root cause, not only the first visible exception.
5. Make the smallest complete fix.
6. Add or update a regression test.
7. Remove temporary debugging logs.
8. Run targeted tests and broader relevant checks.

Avoid blind retries, broad exception handlers, success-shaped fallbacks, or changing validation rules to hide unexpected failures.

## Refactoring

Preserve externally observable behavior unless the user requested a behavior change.

- Refactor one responsibility at a time.
- Keep existing public methods working when callers may depend on them.
- Move shared logic only when the project has clear duplication.
- Remove dead code after references are verified.
- Do not combine unrelated cleanup into a feature commit.
- Update tests alongside signatures and behavior.

## Verification commands

Discover repository-standard commands before running them. Prefer targeted commands first.

For a Composer-based PHP project, typical commands include:

```bash
composer validate
composer install
php -l <changed-file.php>
php <test-file.php>
composer test
vendor/bin/phpunit
vendor/bin/phpstan analyse
vendor/bin/php-cs-fixer fix --dry-run
docker compose config
docker compose up -d --build
```

Use the exact scripts, versions, containers, and test runner already defined by the project. Do not install tools until a missing dependency or manifest change requires them.

## Completion checklist

Before reporting completion:

- The repository structure and existing patterns were respected.
- The requested behavior is implemented end to end.
- Validation, authorization, and error responses are complete.
- Database operations are safe and atomic where required.
- Server-owned business rules cannot be bypassed by clients.
- API documentation matches the implementation.
- Tests cover success and meaningful failure paths.
- Targeted tests pass.
- Build, lint, static analysis, and container checks pass when available.
- No credentials, generated dependencies, logs, or unrelated files are included.
- Changed files are reviewed for secrets and accidental behavior changes.

