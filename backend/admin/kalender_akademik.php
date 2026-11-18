<?php

require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/database.php";

require_role("admin");

$pesan = "";
$error = "";

// Ambil daftar semester
$stmt = $pdo->query("
    SELECT id, nama_semester, tahun_ajaran, status
    FROM semesters
    ORDER BY id DESC
");

$daftarSemester = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tentukan semester yang dipilih
$semesterId = isset($_GET["semester_id"])
    ? (int) $_GET["semester_id"]
    : 0;

// Jika belum memilih, gunakan semester aktif
if ($semesterId === 0) {
    foreach ($daftarSemester as $semester) {
        if ($semester["status"] === "aktif") {
            $semesterId = (int) $semester["id"];
            break;
        }
    }
}

// Proses penyimpanan
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $semesterId = (int) ($_POST["semester_id"] ?? 0);
    $mingguKe = (int) ($_POST["minggu_ke"] ?? 1);

    $statusKrs = $_POST["status_krs"] ?? "ditutup";
    $periodeUjian = $_POST["periode_ujian"] ?? "tidak_ada";

    $mulaiKrs = $_POST["tanggal_mulai_krs"] ?: null;
    $selesaiKrs = $_POST["tanggal_selesai_krs"] ?: null;

    $mulaiUjian = $_POST["tanggal_mulai_ujian"] ?: null;
    $selesaiUjian = $_POST["tanggal_selesai_ujian"] ?: null;

    // Validasi semester
    $cekSemester = $pdo->prepare("
        SELECT id FROM semesters WHERE id = ?
    ");
    $cekSemester->execute([$semesterId]);

    if (!$cekSemester->fetch()) {
        $error = "Semester tidak ditemukan.";
    } elseif ($mingguKe < 1) {
        $error = "Minggu perkuliahan minimal 1.";
    } elseif (!in_array($statusKrs, ["terbuka", "ditutup"], true)) {
        $error = "Status KRS tidak valid.";
    } elseif (!in_array($periodeUjian, ["tidak_ada", "UTS", "UAS"], true)) {
        $error = "Periode ujian tidak valid.";
    } else {

        try {
            $cekKalender = $pdo->prepare("
                SELECT id
                FROM kalender_akademik
                WHERE semester_id = ?
                LIMIT 1
            ");

            $cekKalender->execute([$semesterId]);
            $kalender = $cekKalender->fetch(PDO::FETCH_ASSOC);

            if ($kalender) {

                $stmt = $pdo->prepare("
                    UPDATE kalender_akademik
                    SET minggu_ke = ?,
                        status_krs = ?,
                        periode_ujian = ?,
                        tanggal_mulai_krs = ?,
                        tanggal_selesai_krs = ?,
                        tanggal_mulai_ujian = ?,
                        tanggal_selesai_ujian = ?
                    WHERE semester_id = ?
                ");

                $stmt->execute([
                    $mingguKe,
                    $statusKrs,
                    $periodeUjian,
                    $mulaiKrs,
                    $selesaiKrs,
                    $mulaiUjian,
                    $selesaiUjian,
                    $semesterId
                ]);

            } else {

                $stmt = $pdo->prepare("
                    INSERT INTO kalender_akademik (
                        semester_id,
                        minggu_ke,
                        status_krs,
                        periode_ujian,
                        tanggal_mulai_krs,
                        tanggal_selesai_krs,
                        tanggal_mulai_ujian,
                        tanggal_selesai_ujian
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $semesterId,
                    $mingguKe,
                    $statusKrs,
                    $periodeUjian,
                    $mulaiKrs,
                    $selesaiKrs,
                    $mulaiUjian,
                    $selesaiUjian
                ]);
            }

            header("Location: kalender_akademik.php?semester_id=" . $semesterId . "&saved=1");
            exit;

        } catch (PDOException $e) {
            $error = "Gagal menyimpan kalender akademik.";
        }
    }
}

// Ambil data kalender semester terpilih
$data = [
    "minggu_ke" => 1,
    "status_krs" => "ditutup",
    "periode_ujian" => "tidak_ada",
    "tanggal_mulai_krs" => "",
    "tanggal_selesai_krs" => "",
    "tanggal_mulai_ujian" => "",
    "tanggal_selesai_ujian" => ""
];

if ($semesterId > 0) {
    $stmt = $pdo->prepare("
        SELECT *
        FROM kalender_akademik
        WHERE semester_id = ?
        LIMIT 1
    ");

    $stmt->execute([$semesterId]);
    $hasil = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($hasil) {
        $data = array_merge($data, $hasil);
    }
}

if (isset($_GET["saved"])) {
    $pesan = "Kalender akademik berhasil disimpan.";
}

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Kalender Akademik</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f5f6fc;
            color: #172554;
        }

        .container {
            max-width: 850px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d5d9e5;
            border-radius: 8px;
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #26366f;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .success {
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .error {
            padding: 12px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #4f46e5;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <a class="back" href="../../dashboard_admin_academy_space/code.php">
        &larr; Kembali ke Dashboard
    </a>

    <div class="card">

        <h2>Pengaturan Kalender Akademik</h2>
        <p>Atur informasi semester, KRS, dan periode ujian.</p>

        <?php if ($pesan): ?>
            <div class="success"><?= h($pesan) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="GET">
            <div class="form-group">
                <label>Pilih Semester</label>

                <select name="semester_id" onchange="this.form.submit()" required>
                    <option value="">-- Pilih Semester --</option>

                    <?php foreach ($daftarSemester as $semester): ?>
                        <option
                            value="<?= h($semester["id"]) ?>"
                            <?= $semesterId == $semester["id"] ? "selected" : "" ?>
                        >
                            <?= h($semester["nama_semester"]) ?>
                            <?= h($semester["tahun_ajaran"]) ?>
                            <?= $semester["status"] === "aktif" ? "(Aktif)" : "" ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>
        </form>

        <?php if ($semesterId > 0): ?>

        <form method="POST">

            <input type="hidden" name="semester_id"
                   value="<?= h($semesterId) ?>">

            <div class="form-group">
                <label>Minggu Perkuliahan Ke-</label>
                <input
                    type="number"
                    name="minggu_ke"
                    min="1"
                    value="<?= h($data["minggu_ke"]) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Status Pengisian KRS</label>
                <select name="status_krs" required>
                    <option value="terbuka"
                        <?= $data["status_krs"] === "terbuka" ? "selected" : "" ?>>
                        Terbuka
                    </option>

                    <option value="ditutup"
                        <?= $data["status_krs"] === "ditutup" ? "selected" : "" ?>>
                        Ditutup
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label>Periode Ujian</label>
                <select name="periode_ujian" required>
                    <option value="tidak_ada"
                        <?= $data["periode_ujian"] === "tidak_ada" ? "selected" : "" ?>>
                        Tidak Ada Ujian
                    </option>

                    <option value="UTS"
                        <?= $data["periode_ujian"] === "UTS" ? "selected" : "" ?>>
                        UTS
                    </option>

                    <option value="UAS"
                        <?= $data["periode_ujian"] === "UAS" ? "selected" : "" ?>>
                        UAS
                    </option>
                </select>
            </div>

            <h3>Jadwal Pengisian KRS</h3>

            <div class="grid">
                <div class="form-group">
                    <label>Tanggal Mulai KRS</label>
                    <input type="date" name="tanggal_mulai_krs"
                        value="<?= h($data["tanggal_mulai_krs"]) ?>">
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai KRS</label>
                    <input type="date" name="tanggal_selesai_krs"
                        value="<?= h($data["tanggal_selesai_krs"]) ?>">
                </div>
            </div>

            <h3>Jadwal Ujian</h3>

            <div class="grid">
                <div class="form-group">
                    <label>Tanggal Mulai Ujian</label>
                    <input type="date" name="tanggal_mulai_ujian"
                        value="<?= h($data["tanggal_mulai_ujian"]) ?>">
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai Ujian</label>
                    <input type="date" name="tanggal_selesai_ujian"
                        value="<?= h($data["tanggal_selesai_ujian"]) ?>">
                </div>
            </div>

            <button type="submit">Simpan Kalender Akademik</button>

        </form>

        <?php else: ?>

            <p>Belum ada semester yang tersedia. Silakan tambahkan semester terlebih dahulu.</p>

        <?php endif; ?>

    </div>
</div>

</body>
</html>
