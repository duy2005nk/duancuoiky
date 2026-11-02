CREATE TABLE region (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE company (
    id SERIAL PRIMARY KEY,
    region_id INTEGER NOT NULL REFERENCES region(id),
    name VARCHAR(150) NOT NULL
);

CREATE TABLE product (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    unit VARCHAR(30) NOT NULL
);

CREATE TABLE price_list (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES company(id),
    product_id INTEGER NOT NULL REFERENCES product(id),
    price NUMERIC(12, 2) NOT NULL CHECK (price >= 0),
    valid_from DATE NOT NULL,
    UNIQUE (company_id, product_id, valid_from)
);

CREATE TABLE "order" (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES company(id),
    status VARCHAR(30) NOT NULL CHECK (status IN (
        'APPROVED',
        'PENDING_APPROVAL',
        'REJECTED',
        'DELIVERED'
    )),
    total_amount NUMERIC(12, 2) NOT NULL CHECK (total_amount >= 0),
    created_at TIMESTAMP NOT NULL,
    delivery_date DATE NOT NULL
);

CREATE TABLE order_detail (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL REFERENCES "order"(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES product(id),
    quantity NUMERIC(12, 2) NOT NULL CHECK (quantity > 0),
    applied_price NUMERIC(12, 2) NOT NULL CHECK (applied_price >= 0),
    UNIQUE (order_id, product_id)
);

CREATE INDEX idx_company_region ON company(region_id);
CREATE INDEX idx_price_lookup ON price_list(company_id, product_id, valid_from DESC);
CREATE INDEX idx_order_company ON "order"(company_id);
CREATE INDEX idx_order_status ON "order"(status);
CREATE INDEX idx_order_detail_order ON order_detail(order_id);

CREATE TABLE app_user (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'customer')),
    company_id INTEGER REFERENCES company(id)
);
