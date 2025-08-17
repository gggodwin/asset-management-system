<?php
include '../../../core/dbsys.ini'; // Database connection
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    $category = $_POST['category'];

    // Format the prefix: category ID padded to 3 digits
    $prefix = str_pad($category, 3, '0', STR_PAD_LEFT); // e.g., 101

    // Get the latest barcode for this category
    $stmt = $db->prepare("SELECT barcode FROM dbpis_items WHERE barcode LIKE :prefix ORDER BY barcode DESC LIMIT 1");
    $stmt->execute([':prefix' => $prefix . '%']);
    $latest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($latest) {
        // Extract the numeric sequence after the prefix (last 4 digits)
        $lastCode = (int)substr($latest['barcode'], 3);
        $nextCode = $lastCode + 1;
    } else {
        $nextCode = 1;
    }

    // Build the full barcode: prefix (3 digits) + next code (4 digits)
    $barcode = $prefix . str_pad($nextCode, 4, '0', STR_PAD_LEFT); // e.g., 1010002

    echo json_encode([
        'success' => true,
        'barcode' => $barcode
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>
