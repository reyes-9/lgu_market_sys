<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';

// Get the stall_id from the URL query string
if (isset($_GET['stall_id'])) {
    $stall_id = (int) $_GET['stall_id'];  // Cast to integer to prevent XSS or injection

    // SQL query to fetch ratings data for the given stall_id
    $query = "
    SELECT 
        AVG(rating) AS average_rating,
        COUNT(CASE WHEN rating = 5 THEN 1 END) AS five_star_count,
        COUNT(CASE WHEN rating = 4 THEN 1 END) AS four_star_count,
        COUNT(CASE WHEN rating = 3 THEN 1 END) AS three_star_count,
        COUNT(*) AS total_reviews
    FROM stall_reviews
    WHERE stall_id = :stall_id
    ";

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the data
    $ratingsData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ratingsData) {
        // Get the fetched data
        $averageRating = $ratingsData['average_rating'];
        $fiveStarCount = $ratingsData['five_star_count'];
        $fourStarCount = $ratingsData['four_star_count'];
        $threeStarCount = $ratingsData['three_star_count'];
        $totalReviews = $ratingsData['total_reviews'];

        // Calculate the percentage for each rating (based on total reviews)
        $fiveStarPercentage = ($totalReviews > 0) ? ($fiveStarCount / $totalReviews) * 100 : 0;
        $fourStarPercentage = ($totalReviews > 0) ? ($fourStarCount / $totalReviews) * 100 : 0;
        $threeStarPercentage = ($totalReviews > 0) ? ($threeStarCount / $totalReviews) * 100 : 0;

        // Return data as JSON
        echo json_encode([
            'averageRating' => round($averageRating, 1),  // rounding average rating to 2 decimal places
            'fiveStarCount' => $fiveStarCount,
            'fourStarCount' => $fourStarCount,
            'threeStarCount' => $threeStarCount,
            'fiveStarPercentage' => round($fiveStarPercentage, 2),
            'fourStarPercentage' => round($fourStarPercentage, 2),
            'threeStarPercentage' => round($threeStarPercentage, 2),
            'totalReviews' => $totalReviews
        ]);
    } else {
        // No reviews found for the given stall_id
        echo json_encode([
            'success' => false,
            'message' => 'No reviews found for the given stall.'
        ]);
    }
} else {
    // Handle the case where stall_id is not provided in the query string
    echo json_encode([
        'success' => false,
        'message' => 'No stall_id provided in the query string.'
    ]);
}
