<?php
$hash = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5YmMxSUmGEJOi';
var_dump(password_verify('password123', $hash));  // should be true