
<?php

require_once __DIR__ . '/bootstrap.php';

function block_admin_purchases(): void
{
    $user = current_user();

    if ($user && ($user['role'] ?? '') === 'admin') {
        http_response_code(403);
        exit('Admin accounts cannot purchase products.');
    }
}
