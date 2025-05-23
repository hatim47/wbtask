<?php $start = microtime(true);

error_log("Starting get-comment endpoint...");

try {
    // DB connection (replace with your actual credentials)
    $pdo = new PDO("mysql:host=localhost;dbname=swipayth_task", "swipayth_tasklys", "V#1!(jKQ0T6K");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch comment (example: comment with ID 2)
    $stmt = $pdo->prepare("SELECT * FROM notices WHERE id = :id");
    $stmt->execute(['id' => 2]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    // Respond as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $comment
    ]);

} catch (Exception $e) {
    // Error handling
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Something went wrong'
    ]);
}

$end = microtime(true);
$executionTime = $end - $start;
error_log("Execution time: " . $executionTime . " seconds");
?>