<?php
require_once __DIR__ . '/../../config/db.php';

function getTotalUsers(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
    return $stmt->fetch()['total'];
}

function getActiveReports(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) AS active FROM reports WHERE status = 'pending'");
    return $stmt->fetch()['active'];
}

function getTotalReports(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM reports");
    return $stmt->fetch()['total'];
}

function getResolvedPercentage(PDO $pdo): int {
    $total = getTotalReports($pdo);
    $active = getActiveReports($pdo);
    return $total > 0 ? round((($total - $active) / $total) * 100) : 0;
}