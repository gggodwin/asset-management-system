<?php
require_once '../../../core/dbsys.ini'; // DB connection

function generateUniqueBarcode($category, $db, $maxAttempts = 5) {
    $prefix = str_pad($category, 3, '0', STR_PAD_LEFT);

    for ($i = 0; $i < $maxAttempts; $i++) {
        $stmt = $db->prepare("SELECT barcode FROM dbpis_items WHERE barcode LIKE :prefix ORDER BY barcode DESC LIMIT 1");
        $stmt->execute([':prefix' => $prefix . '%']);
        $latest = $stmt->fetch(PDO::FETCH_ASSOC);

        $lastCode = $latest ? (int)substr($latest['barcode'], 3) : 0;
        $nextCode = $lastCode + 1;
        $newBarcode = $prefix . str_pad($nextCode, 4, '0', STR_PAD_LEFT);

        $checkStmt = $db->prepare("SELECT COUNT(*) FROM dbpis_items WHERE barcode = ?");
        $checkStmt->execute([$newBarcode]);
        if ($checkStmt->fetchColumn() == 0) {
            return $newBarcode;
        }
    }

    throw new Exception("Failed to generate unique barcode after {$maxAttempts} attempts.");
}

try {
    $db->beginTransaction();

    $data = json_decode(file_get_contents("php://input"), true);
    $particular = trim($data['particular'] ?? '');
    $brand = trim($data['brand'] ?? '');
    $category = $data['category'] ?? null;

    if (!$particular || !$brand || !$category) {
        throw new Exception("Missing required fields.");
    }

    // 🔍 Global check: same particular + brand in ANY category
    $check = $db->prepare("
SELECT i.barcode, i.category, c.itcat_name, g.itemcatgrp_name
FROM dbpis_items i
LEFT JOIN dbpis_item_category c ON i.category = c.itcat_id
LEFT JOIN dbpis_itemcategory_group g ON c.itemcatgrp_id = g.itemcatgrp_id
WHERE i.particular = ? AND i.brand = ?

");
    $check->execute([$particular, $brand]);

    if ($check->rowCount() > 0) {
        $existing = $check->fetch(PDO::FETCH_ASSOC);
        $db->rollBack();
    
        $categoryLabel = trim($existing['itcat_name'] . ' (' . $existing['itemcatgrp_name'] . ')');
    
        echo json_encode([
            'success' => false,
            'duplicate' => true,
            'message' => 'Item already exists in another category.',
            'barcode' => $existing['barcode'],
            'category' => $existing['category'],
            'category_label' => $categoryLabel
        ]);
        exit;
    }
    

    // ✅ No duplicate, proceed
    $barcode = generateUniqueBarcode($category, $db);
    $quantity = 0;
    $units = 'pcs';

    $insertItem = $db->prepare("INSERT INTO dbpis_items (barcode, particular, brand, category, quantity, units, last_updated)
        VALUES (?, ?, ?, ?, ?, ?, NOW())");

    $insertItem->execute([
        $barcode,
        $particular,
        $brand,
        $category,
        $quantity,
        $units
    ]);

    $db->commit();

    echo json_encode([
        'success' => true,
        'barcode' => $barcode,
        'message' => 'Item added successfully'
    ]);
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
