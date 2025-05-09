<?php

require_once __DIR__ . '/../../config/db.php';

function getAllRoleRequests() {
    global $pdo;

    $sql = "SELECT rr.*, u.firstName, u.lastName, u.email
            FROM role_requests rr
            JOIN users u ON rr.userID = u.id
            ORDER BY rr.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

function getPendingRoleRequests(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT r.id, r.message, r.proof_image, r.created_at,
               u.id AS user_id, u.firstName, u.lastName, u.email
        FROM role_requests r
        JOIN users u ON r.user_id = u.id
        WHERE r.status = 'pending'
    ");
    return $stmt->fetchAll();
}

function updateRequestStatus(PDO $pdo, int $requestID, string $status): bool {
    $stmt = $pdo->prepare("UPDATE role_requests SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $requestID]);
}

?>
