<?php
declare(strict_types=1);

function renderAlertBox(): void
{
    echo '<div x-show="alert.visible" x-cloak
        x-bind:class="{
            \'alert-success\': alert.type === \'success\',
            \'alert-warning\': alert.type === \'warning\',
            \'alert-error\': alert.type === \'error\'
        }"
        class="alert-box" role="alert" aria-live="polite">
        <p class="font-semibold" x-text="alert.title"></p>
        <p class="mt-1 whitespace-pre-line" x-text="alert.message"></p>
    </div>';
}
