<?php
// students/delete.php
require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
    try {
        $db = Database::getInstance();
        $db->delete('students', 'id = ?', [$id]);
    } catch (Exception $e) {
        // Redirect kèm tham số error để file index.php hiển thị thông báo
        header('Location: index.php?error=1');
        exit;
    }
}
header('Location: index.php?deleted=1');
exit;