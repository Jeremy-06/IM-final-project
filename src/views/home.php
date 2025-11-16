<?php
$pageTitle = 'Home - Lotus Plushies';
ob_start();

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

if (!isset($products)) {
    $productModel = new Product();
    $products = $productModel->getActiveProducts();
}

if (!isset($categories)) {
    $categoryModel = new Category();
    $categories = $categoryModel->getActive();
}

// Helper function to render star ratings
function render_stars_home($rating, $reviewCount) {
    $rating = floatval($rating);
    if ($reviewCount === 0) {
        return '<div class="text-muted small" style="height: 24px; display: flex; align-items: center;">No reviews yet</div>';
    }
    
    $starsHtml = '<div class="d-flex align-items-center" style="height: 24px;">';
    $starsHtml .= '<div class="stars-outer" style="font-size: 1rem; color: #d3d3d3; position: relative; display: inline-block;">';
    $starsHtml .= '<div class="stars-inner" style="color: #ffc107; position: absolute; top: 0; left: 0; white-space: nowrap; overflow: hidden; width: ' . ($rating / 5 * 100) . '%;">';
    for ($i = 0; $i < 5; $i++) {
        $starsHtml .= '<i class="fas fa-star"></i>';
    }
    $starsHtml .= '</div>';
    for ($i = 0; $i < 5; $i++) {
        $starsHtml .= '<i class="far fa-star"></i>';
    }
    $starsHtml .= '</div>';
    $starsHtml .= '<span class="ms-2" style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">(' . $reviewCount . ')</span>';
    $starsHtml .= '</div>';
    return $starsHtml;
}
?>

<!-- Hero Section -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="hero-section shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 50%, var(--pink-medium) 100%); border-radius: 25px; padding: 5rem 3rem; text-align: center;">
            <div class="position-relative">
                <h1 class="display-2 fw-bold text-white mb-3">Welcome to Lotus Plushies</h1>
                <p class="lead text-white mb-4" style="font-size: 1.4rem;">Discover adorable plushies that bring joy and comfort</p>
                <a href="index.php?page=products" class="btn btn-light btn-lg shadow-sm" style="border-radius: 30px; padding: 1rem 3rem; font-weight: 700;"><i class="fas fa-shopping-bag me-2"></i>Shop Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="row mb-5">
    <div class="col-md-12 mb-4 text-center">
        <h2 class="mb-2" style="color: var(--purple-dark); font-weight: 800; font-size: 2.5rem;">Featured Collection</h2>
        <p class="text-muted" style="font-size: 1.1rem;">Our most loved plushies picked just for you</p>
    </div>
</div>

<div class="row g-4">
    <?php if (!empty($products)): ?>
        <?php foreach (array_slice($products, 0, 8) as $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm product-card" style="border-radius: 20px; overflow: hidden;">
                <div class="position-relative">
                    <a href="index.php?page=product&id=<?php echo $product['id']; ?>">
                        <?php if ($product['img_path']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($product['img_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="height: 250px; width: 100%; object-fit: contain; background: #f8f9fa;">
                        <?php else: ?>
                            <div class="card-img-top d-flex align-items-center justify-content-center position-relative" style="height: 250px; aspect-ratio: 1/1; background: linear-gradient(135deg, rgba(139, 95, 191, 0.06) 0%, rgba(255, 159, 191, 0.08) 100%); overflow: hidden;">
                                <!-- Decorative background circles -->
                                <div class="position-absolute" style="top: -15%; right: -8%; width: 120px; height: 120px; background: rgba(139, 95, 191, 0.08); border-radius: 50%; filter: blur(24px);"></div>
                                <div class="position-absolute" style="bottom: -15%; left: -8%; width: 100px; height: 100px; background: rgba(255, 159, 191, 0.09); border-radius: 50%; filter: blur(20px);"></div>

                                <div class="position-relative text-center">
                                    <div class="mb-2" style="animation: float 3s ease-in-out infinite;">
                                        <i class="fas fa-box-open" style="font-size: 3.2rem; background: linear-gradient(135deg, var(--purple-dark) 0%, var(--pink-medium) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                                    </div>
                                    <p class="mb-0 fw-bold" style="color: var(--purple-medium); font-size: 0.85rem; letter-spacing: 0.4px;">No Image</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
                
                <div class="card-body d-flex flex-column" style="padding: 1.5rem;">
                    <h5 class="card-title mb-2" style="color: var(--purple-dark); font-weight: 700; font-size: 1.1rem; min-height: 44px;">
                        <a href="index.php?page=product&id=<?php echo $product['id']; ?>" class="text-decoration-none" style="color: inherit;"><?php echo htmlspecialchars($product['product_name']); ?></a>
                    </h5>
                    
                    <!-- Star Rating -->
                    <div class="mb-3">
                        <?php 
                        $averageRating = $product['average_rating'] ?? 0;
                        $reviewCount = $product['review_count'] ?? 0;
                        echo render_stars_home($averageRating, $reviewCount); 
                        ?>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 fw-bold" style="color: var(--purple-dark); font-size: 1.5rem;">₱<?php echo number_format($product['selling_price'], 2); ?></h4>
                        </div>
                        
                        <a href="index.php?page=product&id=<?php echo $product['id']; ?>" class="btn w-100" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--pink-medium) 100%); color: white; border-radius: 15px; padding: 0.8rem; font-weight: 700;">
                            <i class="fas fa-eye me-2"></i>View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-md-12">
            <div class="alert alert-info text-center">No products available at the moment.</div>
        </div>
    <?php endif; ?>
</div>

<!-- View All Button -->
<div class="row mt-5 mb-5">
    <div class="col-md-12 text-center">
        <a href="index.php?page=products" class="btn btn-lg" style="background: var(--purple-dark); color: white; border-radius: 50px; padding: 1.2rem 4rem; font-weight: 700;">
            <i class="fas fa-th-large me-2"></i>Explore All Products
        </a>
    </div>
</div>

<style>
.product-card { transition: all 0.3s ease; }
.product-card:hover { transform: translateY(-8px); box-shadow: 0 12px 35px rgba(139, 95, 191, 0.2) !important; }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>