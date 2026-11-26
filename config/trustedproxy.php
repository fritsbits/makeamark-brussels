<?php

return [
    // Nginx resolves trusted Cloudflare addresses before passing REMOTE_ADDR to PHP.
    // An explicit config value also suppresses Laravel's Forge hostname fallback.
    'proxies' => [],
];
