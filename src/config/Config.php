<?php

class Config {
    // Database configuration
    const DB_HOST = 'localhost';
    const DB_NAME = 'db_lotus_plushies';
    const DB_USER = 'root';
    const DB_PASS = '';
    
    // Application configuration
    const APP_NAME = 'Lotus Plushies';
    const BASE_URL = 'http://localhost/lotus-plushies/public';
    
    // Timezone configuration (adjust to your timezone)
    const TIMEZONE = 'Asia/Manila'; // Change this to your timezone
    
    // Upload configuration
    const UPLOAD_DIR = __DIR__ . '/../../public/uploads/';
    const UPLOAD_PRODUCTS_DIR = __DIR__ . '/../../public/uploads/products/';
    const UPLOAD_PROFILES_DIR = __DIR__ . '/../../public/uploads/profiles/';
    const MAX_FILE_SIZE = 5242880; // 5MB
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // Pagination
    const ITEMS_PER_PAGE = 12;
    
    // Session configuration
    const SESSION_LIFETIME = 3600; // 1 hour
    
    // Email configuration (Gmail SMTP for real delivery)
    const SMTP_HOST = 'smtp.gmail.com';
    const SMTP_PORT = 587;
    const SMTP_USERNAME = 'lotusplushies@gmail.com';
    const SMTP_PASSWORD = 'qous amyy fxkr iajo'; // Gmail App Password
    const SMTP_ENCRYPTION = 'tls';
    const FROM_EMAIL = 'lotusplushies@gmail.com';
    const FROM_NAME = 'Lotus Plushies';
    
    // Mailtrap configuration (for testing copies)
    const MAILTRAP_HOST = 'sandbox.smtp.mailtrap.io';
    const MAILTRAP_PORT = 2525;
    const MAILTRAP_USERNAME = '1ca49ddcd339a7';
    const MAILTRAP_PASSWORD = 'c4940aa32db690';
    const MAILTRAP_ENCRYPTION = 'tls';
    const TEST_EMAIL = 'test@lotusplushies.mailtrap.io'; // Mailtrap test inbox
    const ENABLE_MAILTRAP = true; // Set to false to disable Mailtrap test copies
}