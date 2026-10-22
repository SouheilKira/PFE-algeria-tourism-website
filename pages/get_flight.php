<?php
require_once '../class/Database.php';

// Get flight ID from request
$flightId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($flightId <= 0) {
    echo json_encode(['error' => 'Invalid flight ID']);
    exit;
}

try {
    // Connect to database
    $db = new Database('localhost', 'root', '', 'pfe');
    $conn = $db->getConnection();

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM vol WHERE id = ?");
    $stmt->bind_param("i", $flightId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($flight = $result->fetch_assoc()) {
        echo json_encode($flight);
    } else {
        echo json_encode(['error' => 'Flight not found']);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?> 