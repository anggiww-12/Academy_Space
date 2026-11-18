<?php

require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/database.php";

// Pastikan hanya admin yang dapat mengakses halaman ini
require_role("admin");

// Ambil seluruh data program studi
$stmt = $pdo->query("
    SELECT
        id,
        kode_prodi,
        nama_prodi,
        jenjang,
        status
    FROM program_studi
    ORDER BY nama_prodi ASC
");

$daftarProdi = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Program Studi - Academy Space</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen p-6">

    <div class="max-w-5xl mx-auto">

        <div class="mb-6">
            <a href="../../dashboard_admin_academy_space/code.php"
               class="text-blue-600 hover:underline">
                &larr; Kembali ke Dashboard
            </a>

            <h1 class="text-2xl font-bold text-slate-800 mt-4">
                Data Program Studi
            </h1>

            <p class="text-slate-500 mt-1">
                Daftar program studi yang terdaftar di Academy Space.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="p-5 border-b border-slate-200">
                <h2 class="font-semibold text-slate-800">
                    Daftar Program Studi
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Total program studi:
                    <?= count($daftarProdi) ?>
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-5 py-3">No.</th>
                            <th class="px-5 py-3">Kode Prodi</th>
                            <th class="px-5 py-3">Nama Program Studi</th>
                            <th class="px-5 py-3">Jenjang</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <?php if (count($daftarProdi) > 0): ?>

                            <?php foreach ($daftarProdi as $index => $prodi): ?>

                                <tr class="hover:bg-slate-50">

                                    <td class="px-5 py-4">
                                        <?= $index + 1 ?>
                                    </td>

                                    <td class="px-5 py-4 font-medium">
                                        <?= htmlspecialchars($prodi['kode_prodi']) ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?= htmlspecialchars($prodi['nama_prodi']) ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?= htmlspecialchars($prodi['jenjang']) ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?= htmlspecialchars($prodi['status']) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5"
                                    class="px-5 py-8 text-center text-slate-500">
                                    Belum ada data program studi.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>
</html>
