<?php

function expect(bool $condition, string $message): void
{
    global $pd_failures;
    if (!isset($pd_failures)) {
        $pd_failures = 0;
    }
    if ($condition) {
        echo "PASS: {$message}\n";
        return;
    }
    $pd_failures++;
    fwrite(STDERR, "FAIL: {$message}\n");
}
