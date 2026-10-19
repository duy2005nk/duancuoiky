<?php
declare(strict_types=1);

function renderAlertBox(): void
{
    echo '<div x-show="alert.visible" x-cloak
        x-bind:class="{
            \'border-green-200 bg-green-50 text-green-800\': alert.type === \'success\',
            \'border-amber-200 bg-amber-50 text-amber-800\': alert.type === \'warning\',
            \'border-red-200 bg-red-50 text-red-800\': alert.type === \'error\'
        }"
        class="mx-5 mb-4 rounded-xl border p-3 text-sm" role="alert" aria-live="polite">
        <p class="font-semibold" x-text="alert.title"></p>
        <p class="mt-1 whitespace-pre-line" x-text="alert.message"></p>
    </div>';
}
