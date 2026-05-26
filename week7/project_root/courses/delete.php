<?php
// courses/delete.php
require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
    try {
        $db = Database::getInstance();
        $db->delete('courses', 'id = ?', [$id]);
    } catch (Exception $e) {
        // Log lỗi
    }
}
header('Location: index.php?deleted=1');
exit;