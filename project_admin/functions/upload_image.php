<?php
header('Content-Type: application/json');

// ตรวจสอบว่ามีไฟล์และโฟลเดอร์ส่งมา
if (!isset($_FILES['file']) || !isset($_POST['folder'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบ']);
    exit;
}

$file = $_FILES['file'];
$folder = basename($_POST['folder']); // ป้องกัน path traversal
$allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// ตรวจสอบนามสกุลไฟล์
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'รองรับเฉพาะ jpg, png, gif, webp']);
    exit;
}

// ✅ สร้าง path ที่ถูกต้อง
$targetDir = __DIR__ . '/../uploads/' . $folder;
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// ✅ ลบรูปเก่า ถ้ามี
if (!empty($_POST['oldFilename'])) {
    $oldPath = $targetDir . '/' . basename($_POST['oldFilename']);
    if (file_exists($oldPath)) {
        unlink($oldPath);
    }
}

// ✅ ตั้งชื่อไฟล์ใหม่และบันทึก
$newName = uniqid('profile_', true) . '.' . $ext;
$targetFile = $targetDir . '/' . $newName;

if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    echo json_encode([
        'status' => 'success',
        'filename' => $newName,
        'path' => "uploads/$folder/$newName"
    ]);
} else {
        error_log("Upload failed:");
    error_log("tmp_name: " . $file['tmp_name']);
    error_log("targetFile: " . $targetFile);
    error_log(print_r($file, true));
    
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'อัปโหลดล้มเหลว']);
}
