<?php
require '../../../core/dbsys.ini';
header('Content-Type: application/json');

function buildFullName($firstname, $midname, $surname, $extension) {
    $parts = array_filter([$firstname, $midname, $surname, $extension], function($part) {
        return !empty(trim($part));
    });
    return implode(' ', $parts);
}

try {
    $id = $_POST['staff_id'];
    $surname = $_POST['surname'];
    $midname = $_POST['midname'];
    $firstname = $_POST['firstname'];
    $extension = $_POST['extension'];
    $dept_id = $_POST['dept_id'];
    $email = $_POST['staff_email'];

    // Fetch old name
    $stmtOld = $db->prepare("SELECT firstname, midname, surname, extension FROM dbpis_staff WHERE staff_id = ?");
    $stmtOld->execute([$id]);
    $old = $stmtOld->fetch(PDO::FETCH_ASSOC);

    if (!$old) {
        throw new Exception("Staff member not found.");
    }

    $old_fullname = buildFullName($old['firstname'], $old['midname'], $old['surname'], $old['extension']);
    $new_fullname = buildFullName($firstname, $midname, $surname, $extension);

    // Update staff table
    $stmtUpdate = $db->prepare("UPDATE dbpis_staff SET surname = ?, midname = ?, firstname = ?, extension = ?, dept_id = ?, staff_email = ? WHERE staff_id = ?");
    $success = $stmtUpdate->execute([$surname, $midname, $firstname, $extension, $dept_id, $email, $id]);

    if ($success && $old_fullname !== $new_fullname) {
        // Update all matching PRS requested_by names
        $stmtPrs = $db->prepare("UPDATE dbpis_prs SET requested_by = ? WHERE requested_by = ?");
        $stmtPrs->execute([$new_fullname, $old_fullname]);
    }

    echo json_encode([
        'success' => true,
        'old_name' => $old_fullname,
        'new_name' => $new_fullname,
        'message' => 'Staff updated successfully and PRS records updated.'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
