<?php

require_once __DIR__ . '/../config/Config.php';

class MailHelper {
    public static function sendOrderSummary($toEmail, $toName, $orderDetails, $orderItems) {
        $logFile = __DIR__ . '/../../public/email_debug.log';
        $logMessage = date('Y-m-d H:i:s') . " - MailHelper: Starting email send to $toEmail for order " . $orderDetails['order_number'] . "\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
        $subject = 'Order Confirmation - Lotus Plushies';
        
        $body = self::buildOrderSummaryEmail($toName, $orderDetails, $orderItems);
        
        // Send to real recipient via Gmail
        $result = self::sendEmail($toEmail, $toName, $subject, $body, 'gmail');
        
        // Also send copy to Mailtrap for testing
        // $testResult = self::sendEmail(Config::TEST_EMAIL, 'Test Recipient', $subject . ' (Test Copy)', $body, 'mailtrap');
        
        $logMessage = date('Y-m-d H:i:s') . " - MailHelper: Real email result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
        return $result; // Return success based on real email
    }
    
    private static function buildOrderSummaryEmail($customerName, $order, $items) {
        $html = "
        <html>
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                /* Simplified mobile styles for better email client compatibility */
                body {
                    font-family: Arial, sans-serif;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: white;
                    border: 1px solid #ddd;
                    font-family: Arial, sans-serif;
                }

                .header {
                    background-color: #8b5fbf;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }

                .header h1 {
                    margin: 0;
                    font-size: 20px;
                    font-weight: bold;
                }

                .header h2 {
                    margin: 8px 0 0 0;
                    font-size: 14px;
                    font-weight: normal;
                }

                .content {
                    padding: 20px;
                }

                .greeting {
                    font-size: 14px;
                    margin-bottom: 15px;
                }

                .order-details {
                    background-color: #f8f9fa;
                    padding: 15px;
                    margin: 15px 0;
                    border-left: 4px solid #8b5fbf;
                }

                .order-details p {
                    margin: 5px 0;
                    font-size: 13px;
                }

                .items-section h3 {
                    color: #8b5fbf;
                    margin-bottom: 10px;
                    font-size: 16px;
                    font-weight: bold;
                }

                .totals-section {
                    background-color: #f8f9fa;
                    padding: 15px;
                    margin-top: 15px;
                    border: 1px solid #ddd;
                }

                .totals-section p {
                    margin: 5px 0;
                    text-align: right;
                    font-size: 13px;
                }

                .totals-section .total {
                    font-size: 16px;
                    font-weight: bold;
                    color: #8b5fbf;
                    border-top: 2px solid #8b5fbf;
                    padding-top: 8px;
                    margin-top: 8px;
                }

                .footer {
                    background-color: #343a40;
                    color: white;
                    padding: 15px;
                    text-align: center;
                }

                .footer p {
                    margin: 5px 0;
                    font-size: 12px;
                }

                .footer a {
                    color: #8b5fbf;
                    text-decoration: none;
                }

                .highlight {
                    background-color: #fff3cd;
                    border: 1px solid #ffeaa7;
                    padding: 10px;
                    margin: 15px 0;
                }

                .highlight p {
                    font-size: 13px;
                    margin: 0;
                }

                /* Mobile-first responsive design for email clients */
                .mobile-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                }

                .mobile-table tr {
                    border: 1px solid #ddd;
                    margin-bottom: 10px;
                    display: block;
                }

                .mobile-table td {
                    display: block;
                    padding: 8px 12px;
                    border: none;
                    border-bottom: 1px solid #eee;
                    text-align: left;
                    font-size: 12px;
                    line-height: 1.4;
                }

                .mobile-table td:last-child {
                    border-bottom: none;
                    font-weight: bold;
                    font-size: 14px;
                    color: #8b5fbf;
                    text-align: left;
                    padding: 12px;
                }

                .mobile-table .product-name {
                    font-weight: bold;
                    background-color: #f8f9fa;
                    border-bottom: 2px solid #8b5fbf;
                    font-size: 13px;
                }

                .mobile-label {
                    font-weight: bold;
                    color: #8b5fbf;
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 2px;
                    display: block;
                }

                /* Use the mobile/card layout everywhere for consistent rendering across clients
                   and apply white-on-purple labels on small screens. Many email clients
                   strip complex CSS, so prefer simple rules. */

                /* Show mobile (card) layout by default so Mailtrap and phones match */
                .items-table {
                    display: none !important;
                }
                .mobile-table {
                    display: table !important;
                }

                /* Accent labels use purple background with white text for mobile/card layout */
                .mobile-table .mobile-label {
                    background-color: #8b5fbf !important;
                    color: #ffffff !important;
                    padding: 4px 6px !important;
                    display: inline-block !important;
                    border-radius: 3px !important;
                }

                .mobile-table .product-name {
                    background-color: #8b5fbf !important;
                    color: #ffffff !important;
                    padding: 10px 12px !important;
                    font-size: 14px !important;
                }

                /* Keep some responsive tweaks for very small devices */
                @media only screen and (max-width: 360px) {
                    .mobile-table td {
                        padding: 8px 10px !important;
                        font-size: 12px !important;
                    }
                    .mobile-table .product-name {
                        font-size: 13px !important;
                        padding: 8px 10px !important;
                    }
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Lotus Plushies</h1>
                    <h2>Order Confirmation</h2>
                </div>

                <div class='content'>
                    <p class='greeting'>Dear <strong>{$customerName}</strong>,</p>

                    <div class='highlight'>
                        <p><strong>Thank you for your order!</strong> We're excited to prepare your plushies for delivery.</p>
                    </div>

                    <div class='order-details'>
                        <p><strong>Order Number:</strong> {$order['order_number']}</p>
                        <p><strong>Order Date:</strong> " . date('F j, Y at g:i a', strtotime($order['created_at'])) . "</p>
                        <p><strong>Status:</strong> " . ucfirst($order['order_status']) . "</p>
                    </div>

                    <div class='items-section'>
                        <h3>Your Order Items</h3>

                        <!-- Desktop Table -->
                        <table class='items-table'>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th style='text-align: center;'>Quantity</th>
                                    <th style='text-align: right;'>Unit Price</th>
                                    <th style='text-align: right;'>Total</th>
                                </tr>
                            </thead>
                            <tbody>";

        foreach ($items as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $html .= "
                                <tr>
                                    <td data-label='Product'><strong>{$item['product_name']}</strong></td>
                                    <td data-label='Quantity' style='text-align: center;'>{$item['quantity']}</td>
                                    <td data-label='Unit Price' class='price-cell'>PHP " . number_format($item['unit_price'], 2) . "</td>
                                    <td data-label='Total' class='price-cell'>PHP " . number_format($itemTotal, 2) . "</td>
                                </tr>";
        }

        $html .= "
                            </tbody>
                        </table>

                        <!-- Mobile Table -->
                        <table class='mobile-table'>
                            <tbody>";

        foreach ($items as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $html .= "
                                <tr>
                                    <td class='product-name'>{$item['product_name']}</td>
                                    <td><span class='mobile-label'>Quantity:</span> {$item['quantity']}</td>
                                    <td><span class='mobile-label'>Unit Price:</span> PHP " . number_format($item['unit_price'], 2) . "</td>
                                    <td><span class='mobile-label'>Line Total:</span> PHP " . number_format($itemTotal, 2) . "</td>
                                </tr>";
        }

        $html .= "
                            </tbody>
                        </table>
                    </div>

                    <div class='totals-section'>
                        <p><strong>Subtotal:</strong> PHP " . number_format($order['subtotal'], 2) . "</p>
                        <p><strong>Shipping:</strong> PHP " . number_format($order['shipping_cost'], 2) . "</p>
                        <p><strong>Tax:</strong> PHP " . number_format($order['tax_amount'], 2) . "</p>
                        <p class='total'>Grand Total: PHP " . number_format($order['total_amount'], 2) . "</p>
                    </div>

                    <div class='highlight'>
                        <p><strong>What's Next?</strong><br>
                        We'll prepare your order and send you shipping updates soon!</p>
                    </div>
                </div>

                <div class='footer'>
                    <p><strong>Questions about your order?</strong></p>
                    <p>Contact us at <a href='mailto:lotusplushies@gmail.com'>lotusplushies@gmail.com</a></p>
                    <p>Thank you for shopping with Lotus Plushies!</p>
                </div>
            </div>
        </body>
        </html>";

        return $html;
    }
    
    private static function sendEmail($to, $toName, $subject, $body, $provider = 'gmail') {
        $logFile = __DIR__ . '/../../public/email_debug.log';
        
        $logMessage = date('Y-m-d H:i:s') . " - MailHelper: Attempting to send email using " . (file_exists(__DIR__ . '/../../vendor/phpmailer/phpmailer/src/PHPMailer.php') ? 'PHPMailer' : 'PHP mail()') . " via $provider\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
        // Try to use PHPMailer if available
        $phpMailerPath = __DIR__ . '/../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        if (file_exists($phpMailerPath)) {
            $logMessage = date('Y-m-d H:i:s') . " - MailHelper: PHPMailer found, using SMTP via $provider\n";
            file_put_contents($logFile, $logMessage, FILE_APPEND);
            
            require_once $phpMailerPath;
            require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/src/SMTP.php';
            require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/src/Exception.php';
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            try {
                $mail->isSMTP();
                
                if ($provider === 'mailtrap') {
                    $mail->Host = Config::MAILTRAP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = Config::MAILTRAP_USERNAME;
                    $mail->Password = Config::MAILTRAP_PASSWORD;
                    $mail->SMTPSecure = Config::MAILTRAP_ENCRYPTION;
                    $mail->Port = Config::MAILTRAP_PORT;
                } else {
                    // Default to Gmail
                    $mail->Host = Config::SMTP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = Config::SMTP_USERNAME;
                    $mail->Password = Config::SMTP_PASSWORD;
                    $mail->SMTPSecure = Config::SMTP_ENCRYPTION;
                    $mail->Port = Config::SMTP_PORT;
                }
                
                $mail->setFrom(Config::FROM_EMAIL, Config::FROM_NAME);
                $mail->addAddress($to, $toName);
                
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                
                $mail->send();
                $logMessage = date('Y-m-d H:i:s') . " - MailHelper: PHPMailer sent successfully via $provider\n";
                file_put_contents($logFile, $logMessage, FILE_APPEND);
                return true;
            } catch (Exception $e) {
                $logMessage = date('Y-m-d H:i:s') . " - MailHelper: PHPMailer failed via $provider: " . $mail->ErrorInfo . "\n";
                file_put_contents($logFile, $logMessage, FILE_APPEND);
                return false;
            }
        } else {
            $logMessage = date('Y-m-d H:i:s') . " - MailHelper: PHPMailer not found, falling back to PHP mail() via $provider\n";
            file_put_contents($logFile, $logMessage, FILE_APPEND);
            
            // Fallback to PHP mail function (less reliable for SMTP)
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: " . Config::FROM_NAME . " <" . Config::FROM_EMAIL . ">" . "\r\n";
            
            $result = mail($to, $subject, $body, $headers);
            $logMessage = date('Y-m-d H:i:s') . " - MailHelper: PHP mail() result via $provider: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
            file_put_contents($logFile, $logMessage, FILE_APPEND);
            return $result;
        }
    }
}