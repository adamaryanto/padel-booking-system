<?php
$files = [
    'resources/views/layouts/admin.blade.php',
    'resources/views/admin/dashboard.blade.php',
    'resources/views/admin/landing/edit.blade.php',
    'resources/views/admin/courts/index.blade.php',
    'resources/views/admin/courts/create.blade.php',
    'resources/views/admin/courts/edit.blade.php',
    'resources/views/admin/bookings/index.blade.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    $brackets = ['[' => ']', '(' => ')', '{' => '}'];
    $stack = [];
    
    for ($i = 0; $i < strlen($content); $i++) {
        $char = $content[$i];
        if (isset($brackets[$char])) {
            $stack[] = [$char, $i];
        } elseif (in_array($char, $brackets)) {
            if (empty($stack)) {
                echo "Unmatched closing $char at $file:$i\n";
            } else {
                $last = array_pop($stack);
                if ($brackets[$last[0]] !== $char) {
                    echo "Mismatched $char at $file:$i (expected closing for {$last[0]} at position {$last[1]})\n";
                }
            }
        }
    }
    
    foreach ($stack as $unclosed) {
        echo "Unclosed {$unclosed[0]} at $file:{$unclosed[1]}\n";
    }
}
echo "Done check.\n";
