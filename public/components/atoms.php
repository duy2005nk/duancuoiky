<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function renderButton(string $label, string $id = '', string $variant = 'primary'): void
{
    $styles = $variant === 'ghost'
        ? 'text-sm font-medium text-slate-500 hover:text-red-600'
        : 'flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-3 font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-300';
    $idAttribute = $id !== '' ? ' id="' . e($id) . '"' : '';
    echo '<button' . $idAttribute . ' type="' . ($variant === 'ghost' ? 'button' : 'submit') . '" class="' . $styles . '">' . e($label) . '</button>';
}

function renderTextInput(string $id, string $label, string $default = ''): void
{
    echo '<label for="' . e($id) . '" class="block text-sm font-medium text-slate-700">' . e($label) . '</label>';
    echo '<input id="' . e($id) . '" name="' . e($id) . '" value="' . e($default) . '" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">';
}

function renderLoadingSpinner(string $id): void
{
    echo '<svg id="' . e($id) . '" class="hidden h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>';
}
