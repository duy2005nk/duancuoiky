<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function renderSubmitButton(string $label = 'Chốt đơn'): void
{
    echo '<button id="submit-order" type="submit" x-bind:disabled="isSubmitting || cart.length === 0"
        x-bind:class="isSubmitting || cart.length === 0
            ? \'cursor-not-allowed bg-[#d9d6ca] text-[#777a72]\'
            : \'bg-[#d95f3d] text-white hover:bg-[#bd4930] shadow-[0_6px_0_#8e3524]\'"
        class="flex min-h-14 w-full items-center justify-center gap-3 px-5 py-4 text-base font-bold transition-transform active:translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#d95f3d] focus:ring-offset-4 disabled:cursor-not-allowed">
        <svg x-show="isSubmitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span x-text="isSubmitting ? \'Đang chốt đơn...\' : ' . "'" . e($label) . "'" . '"></span>
    </button>';
}
