<?php

$pd_failures = 0;
require __DIR__ . '/helpers.php';

foreach (glob(__DIR__ . '/test_*.php') as $file) {
    echo "== " . basename($file) . " ==\n";
    require $file;
}

if ($pd_failures > 0) {
    fwrite(STDERR, "\n{$pd_failures} failure(s)\n");
    exit(1);
}

echo "\nAll tests passed\n";
exit(0);
