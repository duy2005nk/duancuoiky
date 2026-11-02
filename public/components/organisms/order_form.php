<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/atoms/button.php';
require_once __DIR__ . '/../molecules/order_item_row.php';
require_once __DIR__ . '/../molecules/alert_box.php';

function renderPageHeader(): void
{
    echo '<header class="topbar">
        <a class="brand" href="/" aria-label="Trang chủ HKD Trịnh Văn Hùng">
            <span class="brand-mark" aria-hidden="true">TVH</span>
            <span><strong>Trịnh Văn Hùng</strong><small>Thực phẩm B2B</small></span>
        </a>
        <div class="topbar-account">
            <span>' . e((string) ($_SESSION['user']['username'] ?? '')) . '</span>
            <form method="post" action="/logout">
                <button type="submit" class="logout-button">Đăng xuất</button>
            </form>
        </div>
    </header>';
}

function renderWorkspaceRail(): void
{
    echo '<aside class="workspace-rail" aria-label="Điều hướng chính">
        <p class="rail-label">KHÔNG GIAN LÀM VIỆC</p>
        <nav>
            <a class="nav-item" href="/"><span class="nav-icon" aria-hidden="true">01</span>Bảng tổng quan</a>
            <a class="nav-item active" href="/" aria-current="page"><span class="nav-icon" aria-hidden="true">02</span>Đặt hàng</a>
            <a class="nav-item" href="/orders"><span class="nav-icon" aria-hidden="true">03</span>Đơn đã gửi</a>
        </nav>
        <div class="rail-note">
            <span>Ca nhận hàng hôm nay</span>
            <strong>06:30 – 08:30</strong>
        </div>
    </aside>';
}

function renderProductCatalog(array $products): void
{
    echo '<section class="catalog">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Danh mục hôm nay</span>
                <h2>Chọn thực phẩm</h2>
            </div>
            <span class="catalog-count">' . count($products) . ' mặt hàng</span>
        </div>
        <div class="product-list">';

    $itemNumber = 1;
    foreach ($products as $productId => $product) {
        $price = isset($product['price']) ? (float) $product['price'] : 0;
        echo '<article class="product-row">
            <div class="product-monogram" aria-hidden="true">' . e(substr($product['name'], 0, 1)) . '</div>
            <div class="product-copy">
                <h3>' . e($product['name']) . '</h3>
                <p>Đơn vị <strong>' . e($product['unit']) . '</strong> · <strong>' . number_format($price, 0, ',', '.') . ' đ</strong></p>
            </div>
            <button type="button" @click="addItem(' . (int) $productId . ')"
                class="add-button" :class="{ \'is-added\': cart.some(item => item.product_id === ' . (int) $productId . ') }">
                <span aria-hidden="true" x-text="cart.some(item => item.product_id === ' . (int) $productId . ') ? \'✓\' : \'+\'"></span>
                <span x-text="cart.some(item => item.product_id === ' . (int) $productId . ') ? \'Thêm tiếp\' : \'Thêm\'"></span>
            </button>
        </article>';
        $itemNumber++;
    }

    echo '</div></section>';
}

function renderOrderForm(array $products): void
{
    $isCustomer = ($_SESSION['user']['role'] ?? '') === 'customer';
    echo '<aside class="order-panel">
        <form @submit.prevent="submitOrder" novalidate>
            <div class="order-panel-head">
                <div class="order-panel-title">
                    <div>
                        <span class="eyebrow">Phiếu đặt hàng</span>
                        <h2>Đơn mới</h2>
                    </div>
                    <span class="item-count" x-text="cart.length + \' mặt hàng\'"></span>
                </div>
                <label for="company-id">Nhà nhận hàng</label>
                ';
    if ($isCustomer) {
        echo '<div class="company-select company-fixed">Nhà ăn công ty ABC</div>
                <input type="hidden" x-model.number="companyId" value="1">';
    } else {
        echo '<select id="company-id" x-model.number="companyId" class="company-select">
                    <option value="1">Nhà ăn công ty 1</option>
                    <option value="2">Nhà ăn công ty 2</option>
                </select>';
    }
    echo '</div>
            <div class="cart-section">
                <div class="cart-title-row">
                    <h3>Danh sách chọn</h3>
                    <button type="button" @click="clearCart" class="clear-button">Xóa phiếu</button>
                </div>
                <div class="cart-list">';
    renderOrderItemRow();
    echo '</div>
                <div x-show="cart.length === 0" class="empty-cart">
                    <p>Phiếu đang trống</p>
                    <span>Thêm sản phẩm từ danh mục bên trái.</span>
                </div>
                <div class="cart-total" x-show="cart.length > 0">
                    <span>Tạm tính</span>
                    <strong x-text="formatAmount(cartTotal())"></strong>
                </div>
            </div>';
    renderAlertBox();
    echo '<div class="order-panel-action">';
    renderSubmitButton();
    echo '</div>
        </form>
    </aside>';
}

function renderOrderFormScript(array $products): void
{
    $productsJson = json_encode($products, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    echo '<script>
        function orderPage() {
            return {
                products: ' . $productsJson . ',
                companyId: 1,
                cart: [],
                isSubmitting: false,
                alert: { visible: false, type: \'success\', title: \'\', message: \'\' },

                addItem(productId) {
                    const product = this.products[String(productId)];
                    const existing = this.cart.find(item => item.product_id === productId);
                    if (existing) {
                        existing.quantity += 1;
                    } else {
                        this.cart.push({ product_id: productId, name: product.name, unit: product.unit, quantity: 1 });
                    }
                    this.clearAlert();
                },

                removeItem(productId) {
                    this.cart = this.cart.filter(item => item.product_id !== productId);
                    this.clearAlert();
                },

                clearCart() {
                    this.cart = [];
                    this.clearAlert();
                },

                clearAlert() {
                    this.alert = { visible: false, type: \'success\', title: \'\', message: \'\' };
                },

                showAlert(type, title, message) {
                    this.alert = { visible: true, type, title, message };
                },

                formatAmount(amount) {
                    return new Intl.NumberFormat(\'vi-VN\', { style: \'currency\', currency: \'VND\' }).format(Number(amount));
                },

                cartTotal() {
                    return this.cart.reduce((total, item) => {
                        const product = this.products[String(item.product_id)];
                        return total + (Number(item.quantity) || 0) * Number(product?.price || 0);
                    }, 0);
                },

                async submitOrder() {
                    if (this.isSubmitting || this.cart.length === 0) return;

                    const invalidItem = this.cart.find(item => !Number.isFinite(Number(item.quantity)) || Number(item.quantity) <= 0);
                    if (invalidItem) {
                        this.showAlert(\'error\', \'Dữ liệu không hợp lệ\', \'Số lượng mỗi sản phẩm phải lớn hơn 0.\');
                        return;
                    }

                    const payload = {
                        company_id: Number(this.companyId),
                        items: this.cart.map(item => ({
                            product_id: Number(item.product_id),
                            quantity: Number(item.quantity)
                        }))
                    };

                    this.clearAlert();
                    this.isSubmitting = true;
                    const controller = new AbortController();
                    const timeoutId = window.setTimeout(() => controller.abort(), 15000);

                    try {
                        const response = await fetch(\'/api/v1/orders\', {
                            method: \'POST\',
                            headers: { \'Content-Type\': \'application/json\', \'Accept\': \'application/json\' },
                            body: JSON.stringify(payload),
                            signal: controller.signal
                        });

                        const contentType = response.headers.get(\'content-type\') || \'\';
                        const data = contentType.includes(\'application/json\') ? await response.json() : {};

                        if (response.status === 201 || response.status === 202) {
                            const pending = response.status === 202 || data.status === \'PENDING_APPROVAL\';
                            const title = pending ? \'Đơn hàng đang chờ duyệt\' : \'Đặt hàng thành công\';
                            const details = [
                                data.message || (pending ? \'Đơn hàng cần được duyệt thủ công.\' : \'Đơn hàng đã được tạo.\'),
                                \'Mã đơn: \' + data.order_id,
                                \'Tổng tiền: \' + this.formatAmount(data.total_amount)
                            ];
                            if (pending) details.push(\'Đơn được ghi nhận sau 18:00 và sẽ được vendor duyệt thủ công.\');
                            this.showAlert(pending ? \'warning\' : \'success\', title, details.join(\'\\n\'));
                            if (!pending) this.cart = [];
                            return;
                        }

                        if (response.status === 422) {
                            this.showAlert(\'error\', \'Thông tin đặt hàng không hợp lệ\', data.error || data.detail || \'Vui lòng kiểm tra lại thông tin đơn hàng.\');
                            return;
                        }

                        this.showAlert(\'error\', \'Lỗi máy chủ\', data.detail || data.error || \'Không thể tạo đơn hàng. Vui lòng thử lại sau.\');
                    } catch (error) {
                        this.showAlert(
                            \'error\',
                            error.name === \'AbortError\' ? \'Máy chủ phản hồi quá lâu\' : \'Lỗi kết nối mạng\',
                            error.name === \'AbortError\'
                                ? \'Vui lòng kiểm tra kết nối và thử lại.\'
                                : \'Không thể kết nối đến máy chủ. Vui lòng kiểm tra mạng và thử lại.\'
                        );
                    } finally {
                        window.clearTimeout(timeoutId);
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>';
}
