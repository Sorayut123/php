<?php
require_once __DIR__ . '/../../config/db.php';
/*
// ตรวจสอบว่าเป็นการส่งข้อมูลแบบ POST (สำหรับอัปเดตข้อมูลผู้ใช้)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าที่ส่งมาจากฟอร์ม
    $id = (int)$_POST['id']; // แปลงเป็นจำนวนเต็มเพื่อความปลอดภัย
    $FullName = $_POST['FullName'];
    $Username = $_POST['Username'];
    $Email = $_POST['Email'];
    $role_id = (int)$_POST['role_id'];

    // เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูลผู้ใช้ในตาราง members
    $stmt = $conn->prepare("UPDATE members SET FullName=?, Username=?, Email=?, role_id=? WHERE id=?");
    // ผูกค่าตัวแปรเข้ากับคำสั่ง SQL เพื่อป้องกัน SQL Injection
    $stmt->bind_param("sssii", $FullName, $Username, $Email, $role_id, $id);

    // รันคำสั่งและตรวจสอบผลลัพธ์
    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ"; // ส่งข้อความตอบกลับเมื่ออัปเดตสำเร็จ
    } else {
        http_response_code(500); // ส่งสถานะ HTTP 500 เมื่อเกิดข้อผิดพลาด
        echo "ไม่สามารถอัปเดตข้อมูลได้"; // แจ้งข้อผิดพลาด
    }
    exit; // หยุดสคริปต์หลังอัปเดตเสร็จ
}

// ตรวจสอบว่าเป็นการส่งแบบ GET เพื่อขอลบผู้ใช้ (action=delete และมี id)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // แปลง id เป็นจำนวนเต็ม

    // ตรวจสอบก่อนว่าผู้ใช้ที่ต้องการลบมี role_id อะไร เพื่อป้องกันการลบแอดมิน
    $stmtCheck = $conn->prepare("SELECT role_id FROM members WHERE id=?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();

    // ถ้าไม่พบผู้ใช้ที่ต้องการลบ
    if (!$user) {
        http_response_code(404); // ส่งสถานะ 404 Not Found
        echo "ไม่พบผู้ใช้";
        exit;
    }
    // ถ้าผู้ใช้เป็นแอดมิน (role_id = 1) ห้ามลบ
    if ($user['role_id'] == 1) {
        http_response_code(403); // ส่งสถานะ 403 Forbidden
        echo "ไม่อนุญาตให้ลบแอดมิน";
        exit;
    }

    // ถ้าไม่ใช่แอดมิน ให้ลบข้อมูลผู้ใช้
    $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "ลบผู้ใช้สำเร็จ"; // แจ้งผลลัพธ์เมื่อสำเร็จ
    } else {
        http_response_code(500); // แจ้งสถานะข้อผิดพลาด
        echo "ลบผู้ใช้ไม่สำเร็จ";
    }
    exit; // หยุดสคริปต์หลังดำเนินการเสร็จ
}
    */

    // header('Content-Type: application/json');

// ฟังก์ชันดึงข้อมูลผู้ใช้ตาม id


// function getUserById($conn, $userId) {
//     $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
//     if (!$stmt) {
//         http_response_code(500);
//         echo json_encode(["success" => false, "message" => "Prepare statement failed: " . $conn->error]);
//         exit;
//     }
//     $stmt->bind_param("i", $userId);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     if ($result && $result->num_rows === 1) {
//         $user = $result->fetch_assoc();
//         $stmt->close();
//         return $user;
//     }
//     $stmt->close();
//     return null;
// }
// ===== 3. ดึงข้อมูลผู้ใช้ตาม id (สำหรับแสดงรายละเอียด) =====
function getUserById($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    $stmt->close();
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && !isset($_GET['action'])) {
    $userId = (int)$_GET['id'];
    $user = getUserById($conn, $userId);

    header('Content-Type: application/json; charset=utf-8');
    if ($user) {
        echo json_encode([
            "success" => true,
            "user" => $user
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "ไม่พบข้อมูลผู้ใช้"
        ]);
    }
    exit;
}

// ===== 1. อัปเดตข้อมูลผู้ใช้ =====
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $id = (int)$_POST['id'];
//     $FullName = $_POST['FullName'];
//     $Username = $_POST['Username'];
//     $Email = $_POST['Email'];
//     $role_id = (int)$_POST['role_id'];

//     $stmt = $conn->prepare("UPDATE members SET FullName=?, Username=?, Email=?, role_id=? WHERE id=?");
//     $stmt->bind_param("sssii", $FullName, $Username, $Email, $role_id, $id);

//     if ($stmt->execute()) {
//         echo "อัปเดตข้อมูลสำเร็จ";
//     } else {
//         http_response_code(500);
//         echo "ไม่สามารถอัปเดตข้อมูลได้";
//     }
//     exit;
// }
// ===== 1. อัปเดตข้อมูลผู้ใช้ =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $FullName = $_POST['FullName'] ?? '';
    $Username = $_POST['Username'] ?? '';
    $Email = $_POST['Email'] ?? '';
    $role_id = (int)($_POST['role_id'] ?? 2);
    $Password = $_POST['Password'] ?? '';

    // สร้างคำสั่ง SQL แบบไดนามิก
    $fields = "FullName=?, Username=?, Email=?, role_id=?";
    $types = "sssi";
    $params = [$FullName, $Username, $Email, $role_id];

    if (!empty($Password)) {
        if (strlen($Password) < 8) {
            http_response_code(400);
            echo "รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร";
            exit;
        }

        $hashed = password_hash($Password, PASSWORD_DEFAULT);
        $fields .= ", Password=?";
        $types .= "s";
        $params[] = $hashed;
    }

    $fields .= " WHERE id=?";
    $types .= "i";
    $params[] = $id;

    $stmt = $conn->prepare("UPDATE members SET $fields");
    if (!$stmt) {
        http_response_code(500);
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ";
    } else {
        http_response_code(500);
        echo "ไม่สามารถอัปเดตข้อมูลได้";
    }
    exit;
}


// ===== 2. ลบผู้ใช้ (ยกเว้น admin) =====
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmtCheck = $conn->prepare("SELECT role_id FROM members WHERE id=?");
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        http_response_code(404);
        echo "ไม่พบผู้ใช้";
        exit;
    }
    if ($user['role_id'] == 1) {
        http_response_code(403);
        echo "ไม่อนุญาตให้ลบแอดมิน";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "ลบผู้ใช้สำเร็จ";
    } else {
        http_response_code(500);
        echo "ลบผู้ใช้ไม่สำเร็จ";
    }
    exit;
}

// ถ้ามี ?id=xxx ให้ดึงข้อมูลผู้ใช้รายคน
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
//     $id = (int)$_GET['id'];

//     $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result && $result->num_rows === 1) {
//         $user = $result->fetch_assoc();
//         echo json_encode(["success" => true, "user" => $user]);
//     } else {
//         echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลผู้ใช้"]);
//     }
//     exit;
// }

// // ถ้าไม่มี id ดึงข้อมูลผู้ใช้ทั้งหมดที่ไม่ใช่ admin (role_id != 1)
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $stmt = $conn->prepare("SELECT * FROM members WHERE role_id != 1");
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $users = [];
//     if ($result) {
//         while ($row = $result->fetch_assoc()) {
//             $users[] = $row;
//         }
//     }

//     echo json_encode(["success" => true, "users" => $users]);
//     exit;
// }
// var_dump($_GET);

// ฟังก์ชันสำหรับดึงข้อมูลผู้ใช้ตาม ID
// function getUserById($conn, $userId) {
//     $stmt = $conn->prepare("SELECT FullName, Username, Email, BirthDate, Telephone, Gender, ProfileImage FROM members WHERE id = ?");
//     if (!$stmt) {
//         // กรณี prepare ผิดพลาด
//         return null;
//     }
//     $stmt->bind_param("i", $userId);
//     $stmt->execute();
//     $result = $stmt->get_result();
    
//     if ($result && $result->num_rows === 1) {
//         $user = $result->fetch_assoc();
//         $stmt->close();
//         return $user;
//     }
    
//     $stmt->close();
//     return null;
// }
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
//     $id = (int)$_GET['id'];
//     $user = getUserById($conn, $id);
//     if ($user) {
//         echo json_encode([
//             "success" => true,
//             "user" => $user
//         ]);
//     } else {
//         echo json_encode([
//             "success" => false,
//             "message" => "ไม่พบข้อมูลผู้ใช้"
//         ]);
//     }
//     exit;
// }

?>
