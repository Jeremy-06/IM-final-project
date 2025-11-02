<?php
$pageTitle = 'Order Receipt - Lotus Plushies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --purple-dark: #8b5fbf;
            --purple-medium: #a370cc;
            --pink-medium: #ffafc9;
        }
        
        body {
            background: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .receipt-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .receipt-header p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .receipt-content {
            padding: 40px;
        }
        
        .section-title {
            color: var(--purple-dark);
            font-weight: 700;
            font-size: 1.2rem;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--purple-medium);
        }
        
        .section-title:first-child {
            margin-top: 0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
            font-weight: 500;
        }
        
        .info-value {
            color: #333;
            font-weight: 600;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .items-table thead {
            background: #f8f9fa;
        }
        
        .items-table th {
            padding: 15px;
            text-align: left;
            color: var(--purple-dark);
            font-weight: 700;
            border-bottom: 2px solid var(--purple-medium);
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .summary-section {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(139, 95, 191, 0.08) 0%, rgba(255, 159, 191, 0.08) 100%);
            border-radius: 12px;
            border: 2px solid var(--purple-medium);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 1.05rem;
        }
        
        .summary-row.total {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--purple-dark);
            border-top: 3px solid var(--purple-medium);
            padding-top: 20px;
            margin-top: 0;
        }
        
        .receipt-footer {
            background: #f8f9fa;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 0.9rem;
            color: #666;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .btn-print {
            background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 95, 191, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        @media print {
            body {
                background: white;
            }
            .receipt-container {
                box-shadow: none;
                margin: 0;
            }
            .action-buttons {
                display: none;
            }
            .receipt-header {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h1><i class="fas fa-receipt me-2"></i>Lotus Plushies</h1>
            <p>Order Receipt</p>
        </div>
        
        <!-- Content -->
        <div class="receipt-content">
            <!-- Order Information -->
            <div class="section-title">
                <i class="fas fa-hashtag me-2"></i>Order Information
            </div>
            <div class="info-row">
                <span class="info-label">Order Number:</span>
                <span class="info-value"><?php echo htmlspecialchars($order['order_number']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Order Date:</span>
                <span class="info-value"><?php echo date('F d, Y \a\t H:i A', strtotime($order['created_at'])); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Order Status:</span>
                <span class="info-value">
                    <?php
                    $statusConfig = [
                        'pending' => ['color' => '#ffc107', 'label' => '⏱️ Pending'],
                        'shipped' => ['color' => '#007bff', 'label' => '🚚 Shipped'],
                        'completed' => ['color' => '#28a745', 'label' => '✓ Completed'],
                        'cancelled' => ['color' => '#dc3545', 'label' => '✕ Cancelled']
                    ];
                    $config = $statusConfig[$order['order_status']] ?? ['color' => '#6c757d', 'label' => 'Unknown'];
                    ?>
                    <span class="status-badge" style="background: <?php echo $config['color']; ?>20; color: <?php echo $config['color']; ?>;">
                        <?php echo $config['label']; ?>
                    </span>
                </span>
            </div>
            
            <!-- Customer Information -->
            <div class="section-title">
                <i class="fas fa-user me-2"></i>Customer Information
            </div>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">
                    <?php 
                    $fullName = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
                    echo htmlspecialchars($fullName ?: 'Not provided');
                    ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($order['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value"><?php echo htmlspecialchars($order['phone'] ?? 'Not provided'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">
                    <?php 
                    $address = trim(($order['address'] ?? '') . ', ' . ($order['city'] ?? ''));
                    if (!empty($order['postal_code'])) {
                        $address .= ' ' . $order['postal_code'];
                    }
                    if (!empty($order['country'])) {
                        $address .= ', ' . $order['country'];
                    }
                    echo htmlspecialchars($address ?: 'Not provided');
                    ?>
                </span>
            </div>
            
            <!-- Order Items -->
            <div class="section-title">
                <i class="fas fa-shopping-bag me-2"></i>Order Items
            </div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Product</th>
                        <th style="width: 15%; text-align: center;">Quantity</th>
                        <th style="width: 18%; text-align: right;">Unit Price</th>
                        <th style="width: 17%; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($item['display_name']); ?></strong>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $item['quantity']; ?>
                        </td>
                        <td style="text-align: right;">
                            ₱<?php echo number_format($item['unit_price'], 2); ?>
                        </td>
                        <td style="text-align: right;">
                            <strong>₱<?php echo number_format($item['item_total'], 2); ?></strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Order Summary -->
            <div class="summary-section">
                <div class="summary-row total">
                    <span class="info-label">Total Amount:</span>
                    <span>₱<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="receipt-footer">
            <p><strong>Lotus Plushies</strong></p>
            <p>Thank you for your purchase! We appreciate your business.</p>
            <p style="margin-bottom: 0; color: #999;">Receipt Generated on <?php echo date('F d, Y \a\t H:i A'); ?></p>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print me-2"></i>Print Receipt
            </button>
            <a href="admin.php?page=order_detail&id=<?php echo $order['id']; ?>" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Back to Order
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-open print dialog on page load (optional - can be removed if not desired)
        // window.print();
    </script>
</body>
</html>
