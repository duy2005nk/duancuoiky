<?php
declare(strict_types=1);

function renderOrderItemRow(): void
{
    echo '<template x-for="item in cart" :key="item.product_id">
        <div class="grid grid-cols-[minmax(0,1fr)_76px_28px] items-center gap-2 border-b border-[#dedbd0] py-3 last:border-b-0">
            <div class="min-w-0">
                <p class="truncate text-sm font-bold text-[#1d2923]" x-text="item.name"></p>
                <p class="mt-0.5 text-xs text-[#777a72]" x-text="item.unit"></p>
            </div>
            <label class="sr-only" :for="\'quantity-\'+item.product_id" x-text="\'Số lượng \'+item.name"></label>
            <input :id="\'quantity-\'+item.product_id" type="number" min="0.1" step="0.1" inputmode="decimal"
                x-model.number="item.quantity"
                @input="clearAlert()"
                class="w-full border-x-0 border-t-0 border-b border-[#9ba095] bg-transparent px-1 py-2 text-right text-sm font-bold outline-none focus:border-[#d95f3d] focus:ring-0">
            <button type="button" @click="removeItem(item.product_id)"
                class="grid h-8 w-8 place-items-center text-lg text-[#777a72] transition hover:bg-[#f6e4dd] hover:text-[#b9442d]"
                :aria-label="\'Xóa \'+item.name">×</button>
        </div>
    </template>';
}
