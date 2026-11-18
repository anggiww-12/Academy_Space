<?php

function catatAktivitas(
    PDO $pdo,
    string $activity,
    string $description
): bool {

    $userId = $_SESSION["user_id"] ?? null;
    $userRole = $_SESSION["role"] ?? null;

    // Ambil nama pengguna dari database
    $userName = null;

    if ($userId !== null) {

        $stmtUser = $pdo->prepare("
            SELECT name
            FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $stmtUser->execute([
            "id" => $userId
        ]);

        $userName = $stmtUser->fetchColumn() ?: null;
    }

    $ipAddress = $_SERVER["REMOTE_ADDR"] ?? null;

    $stmt = $pdo->prepare("
        INSERT INTO activity_logs (
            user_id,
            user_name,
            user_role,
            activity,
            description,
            ip_address
        )
        VALUES (
            :user_id,
            :user_name,
            :user_role,
            :activity,
            :description,
            :ip_address
        )
    ");

    return $stmt->execute([
        "user_id" => $userId,
        "user_name" => $userName,
        "user_role" => $userRole,
        "activity" => $activity,
        "description" => $description,
        "ip_address" => $ipAddress
    ]);
}
