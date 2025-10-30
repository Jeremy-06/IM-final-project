<?php
$hash = '$2y$12$y7WvpwI/hTes0PZlaltW3OXGNt3RH6f4ItCV6OzJGWxYLg4/8N9A2';
var_dump(password_verify('password123', $hash)); // should be true