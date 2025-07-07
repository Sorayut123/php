<?php


header('Content-Type: application/json');
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save';


    if ($action === 'delete') {
        $id = $_POST['project_id'] ?? null;

        if ($id) {
            // ✅ ดึงชื่อไฟล์ภาพก่อนลบ
            $stmtImg = $conn->prepare("SELECT image FROM training_projects WHERE id = ?");
            $stmtImg->bind_param("i", $id);
            $stmtImg->execute();
            $resultImg = $stmtImg->get_result();
            $imageName = '';
            if ($row = $resultImg->fetch_assoc()) {
                $imageName = $row['image'];
            }
            $stmtImg->close();

            // ✅ ลบข้อมูลจากฐานข้อมูล
            $stmt = $conn->prepare("DELETE FROM training_projects WHERE id = ?");
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();

            if ($result) {
                // ✅ ลบไฟล์ภาพ (ถ้ามี)
                if (!empty($imageName)) {
                    $imagePath = __DIR__ . '/../../uploads/projects/' . basename($imageName);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                echo json_encode(['success' => true, 'message' => 'ลบโครงการและรูปภาพสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบ: ' . $stmt->error]);
            }

            $stmt->close();
            $conn->close();
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่พบ project_id ที่ต้องการลบ']);
            exit;
        }
    }


    // เพิ่ม / แก้ไข
    $id = $_POST['project_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $training_date = $_POST['training_date'] ?? '';
    $location = $_POST['location'] ?? '';
    $status = $_POST['project_status'] ?? '';
    $display_order = ($_POST['display_order'] === 'other') ? null : (int)($_POST['display_order'] ?? 0);
    $imageName = $_POST['project_image'] ?? null;

    // ตรวจสอบและลบ display_order ซ้ำ (ถ้า display_order ไม่ใช่ null และอยู่ใน 1-4)
    if (!is_null($display_order) && in_array($display_order, [1, 2, 3, 4])) {
        if ($id) {
            // แก้ไข: ลบเลขเดียวกันที่ใช้ในเรคคอร์ดอื่น (ไม่ใช่ id นี้)
            $stmtClear = $conn->prepare("UPDATE training_projects SET display_order = NULL WHERE display_order = ? AND id != ?");
            $stmtClear->bind_param("ii", $display_order, $id);
            $stmtClear->execute();
            $stmtClear->close();
        } else {
            // เพิ่มใหม่: ลบเลขเดียวกันทั้งหมดก่อนเพิ่ม
            $stmtClear = $conn->prepare("UPDATE training_projects SET display_order = NULL WHERE display_order = ?");
            $stmtClear->bind_param("i", $display_order);
            $stmtClear->execute();
            $stmtClear->close();
        }
    }

    if ($id) {
        // UPDATE
        $sql = "UPDATE training_projects SET title=?, description=?, date=?, location=?, status=?, display_order=";
        $sql .= is_null($display_order) ? "NULL" : "?";
        $params = [$title, $description, $training_date, $location, $status];

        if (!is_null($display_order)) $params[] = $display_order;

        if ($imageName) {
            $sql .= ", image=?";
            $params[] = $imageName;
        }

        $sql .= " WHERE id=?";
        $params[] = (int)$id;

        $types = "sssss";
        if (!is_null($display_order)) $types .= "i";
        if ($imageName) $types .= "s";
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
    } else {
        // INSERT
        $sql = "INSERT INTO training_projects (title, description, date, location, status, display_order";
        if ($imageName) $sql .= ", image";
        $sql .= ") VALUES (?, ?, ?, ?, ?, ?";
        if ($imageName) $sql .= ", ?";
        $sql .= ")";

        $params = [$title, $description, $training_date, $location, $status, $display_order];
        if ($imageName) $params[] = $imageName;

        $types = "sssss";
        $types .= "i";
        if ($imageName) $types .= "s";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
    }

    if ($result) {
        echo json_encode(['success' => true, 'message' => $id ? 'อัปเดตข้อมูลสำเร็จ' : 'เพิ่มโครงการใหม่สำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึก: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // แสดงข้อมูลทั้งหมด
    $sql = "SELECT id, title, description, date, location, image, display_order, status AS project_status 
            FROM training_projects 
            ORDER BY display_order ASC, id DESC";

    $result = $conn->query($sql);

    if ($result) {
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $projects]);
    } else {
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Method ไม่ถูกต้อง']);
}
