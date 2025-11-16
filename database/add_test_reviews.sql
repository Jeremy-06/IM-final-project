-- Add some test reviews for testing the filter functionality
INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES
(44, 8, 5, 'Amazing dragon plush! The details are incredible and it''s so soft. My kids absolutely love it!', '2025-11-01 10:00:00'),
(44, 11, 4, 'Great quality dragon collectible. A bit pricey but worth it for collectors.', '2025-11-02 14:30:00'),
(44, 8, 3, 'Decent plush, but I expected better stitching quality for the price.', '2025-11-03 09:15:00'),
(50, 11, 5, 'Best panda plush I''ve ever owned! So cuddly and adorable.', '2025-11-02 13:10:00'),
(50, 8, 2, 'The panda is cute but started shedding after a week. Disappointed.', '2025-11-05 08:30:00'),
(52, 11, 4, 'Adorable bunny with floppy ears. My kids fight over who gets to hug it!', '2025-11-03 15:25:00'),
(52, 8, 5, 'Perfect bunny plush! Soft, cute, and exactly as described.', '2025-11-06 10:15:00'),
(44, 11, 1, 'Very disappointed. The dragon arrived damaged and the quality is poor.', '2025-11-07 12:00:00'),
(46, 8, 5, 'Such a gentle and adorable baby elephant! Perfect for my little one.', '2025-11-08 11:45:00'),
(46, 11, 4, 'Beautiful baby elephant plush. Very soft and well-made.', '2025-11-09 14:20:00');

-- Update product ratings based on the reviews
UPDATE products SET
    average_rating = (
        SELECT AVG(rating) FROM reviews WHERE product_id = products.id
    ),
    review_count = (
        SELECT COUNT(*) FROM reviews WHERE product_id = products.id
    )
WHERE id IN (44, 46, 50, 52);