(() => {
    const API_URL = '/api/v1/orders';
    const products = window.ORDER_PRODUCTS || {};
    const cart = new Map();
    const cartList = document.getElementById('cart-list');
    const emptyCart = document.getElementById('empty-cart');
    const form = document.getElementById('order-form');
    const submitButton = document.getElementById('submit-order');
    const submitLabel = document.getElementById('submit-label');
    const loadingIcon = document.getElementById('loading-icon');
    const messageBox = document.getElementById('message');
    let isSubmitting = false;

    function showMessage(type, text) {
        const styles = {
            success: 'border border-green-200 bg-green-50 text-green-800',
            warning: 'border border-amber-200 bg-warning-50 text-warning-800',
            error: 'border border-red-200 bg-red-50 text-red-800'
        };
        messageBox.className = `mx-5 mb-4 rounded-lg p-3 text-sm ${styles[type]}`;
        messageBox.textContent = text;
    }

    function clearMessage() {
        messageBox.className = 'mx-5 mb-4 hidden rounded-lg p-3 text-sm';
        messageBox.textContent = '';
    }

    function renderCart() {
        cartList.replaceChildren();
        if (cart.size === 0) {
            emptyCart.classList.remove('hidden');
            submitButton.disabled = true;
            return;
        }

        emptyCart.classList.add('hidden');
        submitButton.disabled = isSubmitting;
        cart.forEach((quantity, productId) => {
            const product = products[productId];
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2 rounded-lg border border-slate-200 p-3';
            row.innerHTML = `
                <p class="min-w-0 flex-1 truncate text-sm font-semibold text-slate-800">${product.name}</p>
                <label class="sr-only" for="quantity-${productId}">Số lượng ${product.name}</label>
                <input id="quantity-${productId}" data-cart-quantity="${productId}" type="number" min="0.1" step="0.1" inputmode="decimal" required value="${quantity}"
                    class="w-20 rounded-md border border-slate-300 px-2 py-1.5 text-right text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <span class="w-6 text-xs text-slate-500">${product.unit}</span>
                <button type="button" data-remove-product="${productId}" class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-red-600" aria-label="Xóa ${product.name}">×</button>`;
            cartList.appendChild(row);
        });
    }

    function setSubmitting(submitting) {
        isSubmitting = submitting;
        submitButton.disabled = submitting || cart.size === 0;
        submitButton.classList.toggle('opacity-70', submitting);
        submitButton.classList.toggle('cursor-wait', submitting);
        loadingIcon.classList.toggle('hidden', !submitting);
        submitLabel.textContent = submitting ? 'Đang chốt đơn...' : 'Chốt Đơn';
    }

    document.querySelectorAll('[data-add-product]').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.addProduct;
            cart.set(id, cart.get(id) || 1);
            clearMessage();
            renderCart();
        });
    });

    cartList.addEventListener('input', event => {
        const input = event.target.closest('[data-cart-quantity]');
        if (!input) return;
        const quantity = Number(input.value);
        if (Number.isFinite(quantity) && quantity > 0) cart.set(input.dataset.cartQuantity, quantity);
    });

    cartList.addEventListener('click', event => {
        const button = event.target.closest('[data-remove-product]');
        if (!button) return;
        cart.delete(button.dataset.removeProduct);
        clearMessage();
        renderCart();
    });

    document.getElementById('clear-cart').addEventListener('click', () => {
        cart.clear();
        clearMessage();
        renderCart();
    });

    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (isSubmitting || cart.size === 0) return;
        clearMessage();
        if ([...cart.values()].some(quantity => !Number.isFinite(quantity) || quantity <= 0)) {
            showMessage('error', 'Số lượng mỗi sản phẩm phải lớn hơn 0.');
            return;
        }

        const payload = {
            company_id: Number(document.getElementById('company-id').value),
            items: [...cart.entries()].map(([productId, quantity]) => ({
                product_id: Number(productId),
                quantity
            }))
        };

        setSubmitting(true);
        const controller = new AbortController();
        const timeoutId = window.setTimeout(() => controller.abort(), 15000);
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
                signal: controller.signal
            });
            const contentType = response.headers.get('content-type') || '';
            const data = contentType.includes('application/json') ? await response.json() : {};

            if (response.status === 201) {
                cart.clear();
                renderCart();
                showMessage('success', data.message || 'Đặt hàng thành công.');
                return;
            }
            if (response.status === 202) {
                showMessage('warning', data.message || 'Đơn hàng đang chờ duyệt do đặt sau 18:00.');
                return;
            }
            showMessage('error', data.error || data.message || `Hệ thống trả về lỗi HTTP ${response.status}.`);
        } catch (error) {
            showMessage('error', error.name === 'AbortError'
                ? 'Máy chủ phản hồi quá lâu. Vui lòng kiểm tra kết nối và thử lại.'
                : 'Không thể kết nối đến máy chủ. Vui lòng kiểm tra mạng và thử lại.');
        } finally {
            window.clearTimeout(timeoutId);
            setSubmitting(false);
        }
    });

    renderCart();
})();
