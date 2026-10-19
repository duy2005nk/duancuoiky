INSERT INTO region (name) VALUES
    ('Khu vực Bắc'),
    ('Khu vực Nam');

INSERT INTO company (region_id, name) VALUES
    (1, 'Nhà ăn Công ty ABC'),
    (1, 'Nhà ăn Công ty XYZ'),
    (2, 'Nhà ăn Công ty Minh Phát');

INSERT INTO product (name, unit) VALUES
    ('Đậu phụ', 'Bìa'),
    ('Rau củ tổng hợp', 'kg'),
    ('Rau sạch theo mùa', 'kg');

INSERT INTO price_list (company_id, product_id, price, valid_from) VALUES
    (1, 1, 25000, '2026-01-01'),
    (1, 2, 18000, '2026-01-01'),
    (1, 3, 22000, '2026-01-01'),
    (2, 1, 24000, '2026-01-01'),
    (2, 2, 17500, '2026-01-01'),
    (2, 3, 21500, '2026-01-01'),
    (3, 1, 26000, '2026-01-01'),
    (3, 2, 18500, '2026-01-01'),
    (3, 3, 22500, '2026-01-01');
