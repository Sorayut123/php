<?php
session_start();
require_once '../config/db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

$memberID = $_SESSION['user_id']; // กำหนด memberID จาก session


$portfolioID = intval($_GET['id']);

// โหลดผลงานที่จะแก้ไข
$stmt = $conn->prepare("SELECT * FROM performance WHERE PortfolioID = ? AND MemberID = ?");
$stmt->bind_param("ii", $portfolioID, $memberID);
$stmt->execute();
$result = $stmt->get_result();
$work = $result->fetch_assoc();
$stmt->close();

if (!$work) {
    echo "ไม่พบผลงานนี้";
    exit();
}

// โหลดหมวดหมู่
$categoryList = [];
$catQuery = $conn->query("SELECT id, category_name FROM categories ORDER BY category_name ASC");
if ($catQuery) {
    $categoryList = $catQuery->fetch_all(MYSQLI_ASSOC);
}

// โหลดรูปภาพทั้งหมดของผลงาน
$stmtImgs = $conn->prepare("SELECT ImageID, ImageURL FROM portfolio_images WHERE PortfolioID = ?");
$stmtImgs->bind_param("i", $portfolioID);
$stmtImgs->execute();
$resultImgs = $stmtImgs->get_result();
$images = $resultImgs->fetch_all(MYSQLI_ASSOC);
$stmtImgs->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $categoryID = intval($_POST['category']);

    $stmt = $conn->prepare("UPDATE performance SET Title = ?, Description = ?, CategoryID = ? WHERE PortfolioID = ? AND MemberID = ?");
    $stmt->bind_param("ssiii", $title, $description, $categoryID, $portfolioID, $memberID);

    if ($stmt->execute()) {
        $stmt->close();

        // ลบรูปภาพที่เลือก
        if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $deleteImageID) {
                $deleteImageID = intval($deleteImageID);

                // ดึง path รูปก่อนลบไฟล์จริง
                $stmtImg = $conn->prepare("SELECT ImageURL FROM portfolio_images WHERE ImageID = ? AND PortfolioID = ?");
                $stmtImg->bind_param("ii", $deleteImageID, $portfolioID);
                $stmtImg->execute();
                $resultImg = $stmtImg->get_result();
                $imgData = $resultImg->fetch_assoc();
                $stmtImg->close();

                if ($imgData) {
                    $filePath = "../" . $imgData['ImageURL'];
                    if (file_exists($filePath)) {
                        unlink($filePath); // ลบไฟล์จริง
                    }

                    // ลบข้อมูลในฐานข้อมูล
                    $stmtDel = $conn->prepare("DELETE FROM portfolio_images WHERE ImageID = ? AND PortfolioID = ?");
                    $stmtDel->bind_param("ii", $deleteImageID, $portfolioID);
                    $stmtDel->execute();
                    $stmtDel->close();
                }
            }
        }

        // อัปโหลดรูปภาพใหม่ถ้ามี
        if (isset($_FILES['images'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $fileType = $_FILES['images']['type'][$key];
                $fileError = $_FILES['images']['error'][$key];
                if ($fileError === 0 && in_array($fileType, $allowedTypes)) {
                    $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                    $imageName = time() . "_" . uniqid() . "." . $ext;
                    $targetPath = "../image/" . $imageName;

                    if (move_uploaded_file($tmp_name, $targetPath)) {
                        $imageURL = "image/" . $imageName;
                        $stmt2 = $conn->prepare("INSERT INTO portfolio_images (PortfolioID, ImageURL) VALUES (?, ?)");
                        $stmt2->bind_param("is", $portfolioID, $imageURL);
                        $stmt2->execute();
                        $stmt2->close();
                    }
                }
            }
        }

        header("Location: works_list.php");
        exit();
    } else {
        echo "แก้ไขผลงานล้มเหลว: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <title>แก้ไขผลงาน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <?php include("sidebar.php"); ?>

    <main class="flex-1 max-w-4xl mx-auto p-8">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-green-700 mb-6 text-center">แก้ไขผลงาน</h2>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">ชื่อผลงาน</label>
                    <input type="text" name="title" required
                        value="<?= htmlspecialchars($work['Title']) ?>"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">รายละเอียดผลงาน</label>
                    <textarea name="description" rows="4" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?= htmlspecialchars($work['Description']) ?></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">หมวดหมู่</label>
                    <select name="category" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- กรุณาเลือกหมวดหมู่ --</option>
                        <?php foreach ($categoryList as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($work['CategoryID'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- แสดงรูปภาพเก่า พร้อม checkbox ให้ลบ -->
                <?php if (count($images) > 0): ?>
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700 mb-2">รูปภาพปัจจุบัน</label>
                    <div class="flex flex-wrap gap-4">
                        <?php foreach ($images as $img): ?>
                        <div class="relative">
                            <img src="../<?= htmlspecialchars($img['ImageURL']) ?>" alt="รูปผลงาน"
                                class="w-24 h-24 object-cover rounded-md border border-gray-300">
                            <label class="flex items-center space-x-2 mt-1 cursor-pointer">
                                <input type="checkbox" name="delete_images[]" value="<?= $img['ImageID'] ?>"
                                    class="form-checkbox text-red-600" />
                                <span class="text-sm text-red-600">ลบรูปนี้</span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">เพิ่มรูปภาพใหม่ <span
                            class="text-sm text-gray-500">(เลือกได้หลายภาพ)</span></label>
                    <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/gif"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200" />
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="bg-green-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-green-700 transition">บันทึกการแก้ไข</button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="works_list.php" class="text-green-600 hover:underline">← กลับไปหน้าผลงาน</a>
            </div>
        </div>
    </main>

</body>

</html>
