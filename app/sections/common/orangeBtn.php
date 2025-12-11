<?php
function btn_orange($slot, string $extraClasses = ''): string
{
    if (is_callable($slot)) {
        ob_start();
        $slot();
        $slot = ob_get_clean();
    }

    $classes = trim(
        "inline-flex items-center justify-center " .
        "px-8 py-3 " .
        "min-w-[118px] min-h-[43px] " .
        "bg-[url('/assets/misc/orangeBtn.webp')] bg-[length:100%_100%] " .
        "text-white text-xl font-extrabold text-center " .
        $extraClasses
    );

    return '<button type="button" class="' . $classes . '">' . $slot . '</button>';
}
