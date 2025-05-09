<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'], $_POST['new_role'])) {
        $userID = $_POST['user_id'];
        $newRole = $_POST['new_role'];

        // Update role
        $sql = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':role' => $newRole,
            ':id' => $userID
        ]);

        // Optional: delete from role_requests table
        $sql = "DELETE FROM role_requests WHERE userID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $userID]);

        header("Location: /MapaAyos/admin/dashboard");
        exit;
    }
}