<?php
declare(strict_types=1);

function renderOrderItemRow(): void
{
    echo '<template x-for="item in cart" :key="item.product_id">
        <div class="cart-row">
            <div class="cart-product">
                <p x-text="item.name"></p>
                <span x-text="item.unit"></span>
            </div>
                <div class="quantity-control">
                    <label :for="\'quantity-\'+item.product_id" x-text="\'Số lượng \'"></label>
                    <input :id="\'quantity-\'+item.product_id" type="number" min="0.1" step="0.1" inputmode="decimal"
                        x-model.number="item.quantity"
                        @input="clearAlert()"
                        :aria-label="\'Số lượng \'+item.name"
                        class="quantity-input">
                </div>
            <button type="button" @click="removeItem(item.product_id)"
                class="remove-button"
                :aria-label="\'Xóa \'+item.name">×</button>
        </div>
    </template>';
}
