<?php
echo password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]);