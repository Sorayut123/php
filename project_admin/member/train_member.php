<?php 
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$memberID = $_SESSION['user_id']; // กำหนด memberID จาก session


// ดึงข้อมูลโครงการที่สมาชิกลงทะเบียนไว้
$sql = "SELECT 
            pr.id AS registration_id,
            tp.title AS project_title,
            tp.date AS training_date,
            tp.location,
            pr.created_at,
            tp.project_status AS project_status
        FROM project_registrations pr
        JOIN training_projects tp ON pr.project_id = tp.id
        WHERE pr.member_id = ?
        ORDER BY pr.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $memberID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โครงการที่คุณลงทะเบียน</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-green-600 mb-6">📋 โครงการที่คุณลงทะเบียนไว้</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">ชื่อโครงการ</th>
                        <th class="px-6 py-3">วันจัดโครงการ</th>
                        <th class="px-6 py-3">สถานะโครงการ</th>
                        <th class="px-6 py-3">สถานที่</th>
                        <th class="px-6 py-3">วันที่ลงทะเบียน</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='hover:bg-gray-50'>";
                        echo "<td class='px-6 py-4'>{$i}</td>";
                        echo "<td class='px-6 py-4'>{$row['project_title']}</td>";
                        echo "<td class='px-6 py-4'>{$row['project_status']}</td>";
                        echo "<td class='px-6 py-4'>" . ($row['training_date'] ?? '-') . "</td>";
                        echo "<td class='px-6 py-4'>" . ($row['location'] ?? '-') . "</td>";
                        echo "<td class='px-6 py-4'>" . date("d/m/Y H:i", strtotime($row['created_at'])) . "</td>";
                        echo "</tr>";
                        $i++;
                    }

                    if ($i === 1) {
                        echo "<tr><td colspan='5' class='px-6 py-4 text-center text-gray-500'>ยังไม่มีการลงทะเบียนโครงการ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>