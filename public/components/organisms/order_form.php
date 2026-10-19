<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/atoms/button.php';
require_once __DIR__ . '/../molecules/order_item_row.php';
require_once __DIR__ . '/../molecules/alert_box.php';

function renderPageHeader(): void
{
    echo '<header class="border-b border-[#263d32] bg-[#18392b] text-[#fffdf5]">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6 sm:py-5">
            <div class="flex items-center gap-3">
                <div class="grid h-11 w-11 shrink-0 place-items-center border border-[#8fb39e] text-lg font-bold">H</div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-[#b9d0c2]">Phiếu đặt hàng</p>
                    <h1 class="font-display text-lg leading-tight sm:text-2xl">HKD Trịnh Văn Hùng</h1>
                </div>
            </div>
            <div class="border-l border-[#6c8a77] pl-4 text-right">
                <p class="text-[11px] font-semibold text-[#b9d0c2]">Chốt đơn trước</p>
                <p class="font-display text-xl text-[#f4c95d]">18:00</p>
            </div>
        </div>
    </header>';
}

function renderProductCatalog(array $products): void
{
    echo '<section>
        <div class="mb-5 border-b border-[#b9b7ad] pb-5">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-[#a54a33]">Danh mục hôm nay</p>
            <h2 class="font-display text-3xl text-[#18392b] sm:text-4xl">Thực phẩm cho nhà ăn</h2>
            <p class="mt-2 max-w-xl text-sm leading-6 text-[#687068]">Chọn sản phẩm cần đặt. Số lượng có thể điều chỉnh trong phiếu bên cạnh.</p>
        </div>
        <div class="divide-y divide-[#c9c6ba] border-y border-[#c9c6ba]">';

    $itemNumber = 1;
    foreach ($products as $productId => $product) {
        echo '<article class="group grid grid-cols-[44px_minmax(0,1fr)_auto] items-center gap-3 py-4 sm:grid-cols-[54px_minmax(0,1fr)_auto] sm:gap-5 sm:py-5">
            <span class="font-display text-2xl text-[#aaa99f]">' . str_pad((string) $itemNumber, 2, '0', STR_PAD_LEFT) . '</span>
            <div>
                <h3 class="text-base font-bold text-[#25352d] sm:text-lg">' . e($product['name']) . '</h3>
                <p class="mt-1 text-sm text-[#777b73]">Đơn vị đặt hàng · ' . e($product['unit']) . '</p>
            </div>
            <button type="button" @click="addItem(' . (int) $productId . ')"
                class="border border-[#315844] px-3 py-2 text-sm font-bold text-[#315844] transition hover:bg-[#315844] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#315844] focus:ring-offset-2 sm:px-4">+ Thêm</button>
        </article>';
        $itemNumber++;
    }

    echo '</div></section>';
}

function renderOrderForm(array $products): void
{
    echo '<aside class="border border-[#c9c6ba] bg-[#fffdf7] shadow-[8px_8px_0_#dcd8c9] lg:sticky lg:top-5">
        <form @submit.prevent="submitOrder" novalidate>
            <div class="border-b border-[#c9c6ba] bg-[#ecede1] p-5">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#69756b]">Phiếu số</p>
                        <h2 class="mt-1 font-display text-2xl text-[#18392b]">Đơn đặt hàng</h2>
                    </div>
                    <span class="border-b border-[#18392b] pb-1 text-sm font-bold text-[#18392b]" x-text="cart.length + \' mặt hàng\'"></span>
                </div>
                <label for="company-id" class="mt-5 block text-sm font-bold text-[#445148]">Nhà nhận hàng</label>
                <select id="company-id" x-model.number="companyId"
                    class="mt-2 w-full border border-[#aeb2a7] bg-[#fffdf7] px-3 py-3 text-sm outline-none focus:border-[#315844] focus:ring-2 focus:ring-[#315844]/20">
                    <option value="1">Nhà ăn công ty 1</option>
                    <option value="2">Nhà ăn công ty 2</option>
                </select>
            </div>
            <div class="p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="font-bold text-[#25352d]">Danh sách chọn</h3>
                    <button type="button" @click="clearCart" class="text-xs font-bold text-[#777b73] underline decoration-[#777b73]/40 underline-offset-4 hover:text-[#b9442d]">Xóa phiếu</button>
                </div>
                <div class="space-y-3">';
    renderOrderItemRow();
    echo '</div>
                <div x-show="cart.length === 0" class="my-2 border border-dashed border-[#c3c4b9] px-4 py-9 text-center">
                    <p class="font-display text-lg text-[#777b73]">Phiếu đang trống</p>
                    <p class="mt-1 text-xs leading-5 text-[#999c94]">Chọn một sản phẩm ở danh mục để bắt đầu.</p>
                </div>
            </div>';
    renderAlertBox();
    echo '<div class="border-t border-[#c9c6ba] bg-[#f7f4ea] p-5">';
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
