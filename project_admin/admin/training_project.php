<?php
session_start();
require_once '../config/db.php';
// require_once '../includes/auth_check.php';

// ดึงข้อมูลโครงการอบรมจากฐานข้อมูล
// $sql = "SELECT id, title, description, date AS training_date, location, status AS project_status, display_order, image AS image_path FROM training_projects ORDER BY display_order ASC, id DESC";
// $result = $conn->query($sql);
$sql = "SELECT id, title, description, date AS training_date, location, status AS project_status, display_order, image AS image_path 
        FROM training_projects 
        ORDER BY 
            CASE 
                WHEN display_order IS NULL OR display_order = 0 THEN 999 
                ELSE display_order 
            END ASC, 
            id DESC";
$result = $conn->query($sql);

$projects = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
} else {
    $projects = [];
}

// ฟังก์ชันแปลงสถานะเป็นภาษาไทย
function getStatusText($status)
{
    $statusMap = [
        'open' => 'เปิดรับสมัคร',
        'closed' => 'ปิดรับสมัคร',
        'in_progress' => 'กำลังดำเนินการ',
        'completed' => 'เสร็จสิ้นแล้ว'
    ];
    return $statusMap[$status] ?? $status;
}

// ฟังก์ชันแปลงสถานะเป็นสีใน CSS
function getStatusColorClass($status)
{
    $colorMap = [
        'open' => 'bg-green-100 text-green-800',
        'closed' => 'bg-red-100 text-red-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'completed' => 'bg-gray-100 text-gray-800'
    ];
    return $colorMap[$status] ?? 'bg-gray-100 text-gray-800';
}

// ฟังก์ชันแปลงวันที่เป็นรูปแบบไทย
function formatDateForDisplay($dateString)
{
    if (!$dateString) return '';

    $date = new DateTime($dateString);
    $thaiMonths = [
        'ม.ค.',
        'ก.พ.',
        'มี.ค.',
        'เม.ย.',
        'พ.ค.',
        'มิ.ย.',
        'ก.ค.',
        'ส.ค.',
        'ก.ย.',
        'ต.ค.',
        'พ.ย.',
        'ธ.ค.'
    ];

    $day = $date->format('j');
    $month = $thaiMonths[$date->format('n') - 1];
    $year = $date->format('Y');

    return "{$day} {$month} {$year}";
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลโครงการอบรม</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .main-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            margin: 1rem;
        }

        .header-gradient {
            background-color: #ff6b35;
            border-radius: 20px 20px 0 0;
        }

        .profile-img {
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .order-badge {
            background: #ff7a00;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            min-width: 30px;
            text-align: center;
        }

        /* .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        } */
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            /* หรือ left: 10px ถ้าต้องการซ้าย */
            z-index: 10;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .training-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
            position: relative;
        }

        .training-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 0;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff7a00;
            box-shadow: 0 0 0 3px rgba(255, 122, 0, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .image-upload-container {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload-container:hover {
            border-color: #ff7a00;
            background-color: #fff7ed;
        }

        .image-upload-container.dragover {
            border-color: #ff7a00;
            background-color: #fff7ed;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .image-upload-input {
            display: none;
        }

        .remove-image-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .remove-image-btn:hover {
            background: rgba(239, 68, 68, 1);
        }
    </style>
</head>

<body>
    <div class="flex min-h-screen">
        <?php include '../includes/sidebarAdmin.php'; ?>

        <div class="flex-1 p-1">
            <div class="main-content">
                <!-- Header -->
                <div class="header-gradient p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">
                                <i class="fas fa-book-open mr-3"></i>
                                ข้อมูลโครงการอบรม
                            </h1>
                            <p class="text-white">จัดการวิดีโอและเนื้อหาการเรียนรู้สำหรับผู้ใช้งาน</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-white font-medium"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                            <img src="https://via.placeholder.com/40" class="rounded-full w-12 h-12 profile-img" alt="Profile">
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold">ข้อมูลโครงการอบรม</h1>
                        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow transition-colors" onclick="openAddModal()">
                            + เพิ่มโครงการใหม่
                        </button>
                    </div>

                    <!-- Training Projects Grid -->
                    <div id="projectsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($projects as $project): ?>
                            <div class="training-card bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <div class="relative">
                                    <img src="../uploads/projects/<?php echo htmlspecialchars($project['image_path']); ?>" alt="Profile Image" />


                                    <span class="status-badge <?php echo getStatusColorClass($project['project_status']); ?>">
                                        <?php echo getStatusText($project['project_status']); ?>
                                    </span>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h2 class="text-lg font-bold"><?php echo htmlspecialchars($project['title']); ?></h2>
                                        <?php
                                        // แก้ไขเงื่อนไขการแสดงหมายเลขกำกับ
                                        $displayOrder = (int)$project['display_order'];
                                        if ($displayOrder >= 1 && $displayOrder <= 4):
                                        ?>
                                            <span class="order-badge text-xs bg-gray-200 px-2 py-1 rounded">#<?php echo $displayOrder; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        วันที่: <?php echo formatDateForDisplay($project['training_date']); ?> |
                                        สถานที่: <?php echo htmlspecialchars($project['location']); ?>
                                    </p>
                                    <p class="text-sm text-gray-700 mb-4"><?php echo htmlspecialchars($project['description']); ?></p>

                                    <div class="flex flex-wrap justify-end gap-2">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                            onclick="editProject(<?php echo (int)$project['id']; ?>)">แก้ไข</button>
                                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                            onclick="deleteProject(<?= (int)$project['id']; ?>)">
                                            ลบ
                                        </button>

                                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                            ดูรายชื่อผู้ลงทะเบียน
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">แก้ไขข้อมูลโครงการ</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4">
                <form id="projectForm">
                    <!-- <input type="hidden" id="projectId" name="project_id"> -->
                    <input type="hidden" id="projectId" name="project_id" />

                    <input type="hidden" id="currentImagePath" name="current_image_path">

                    <div class="form-group">
                        <label class="form-label" for="projectTitle">ชื่อโครงการ</label>
                        <input type="text" id="projectTitle" name="title" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="projectDescription">รายละเอียด</label>
                        <textarea id="projectDescription" name="description" class="form-input form-textarea" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="projectDate">วันที่อบรม</label>
                            <input type="date" id="projectDate" name="training_date" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="projectLocation">สถานที่</label>
                            <input type="text" id="projectLocation" name="location" class="form-input" required>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="form-group">
                        <label class="form-label">รูปภาพโครงการ</label>
                        <div class="image-upload-container" id="imageUploadContainer" onclick="document.getElementById('imageUploadInput').click()">
                            <input type="file" id="imageUploadInput" name="project_image" class="image-upload-input" accept="image/*" onchange="handleImageUpload(this)">
                            <div id="uploadPrompt">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 mb-1">คลิกเพื่อเลือกรูปภาพ หรือลากไฟล์มาวาง</p>
                                <p class="text-sm text-gray-500">รองรับไฟล์ JPG, PNG, GIF (ขนาดไม่เกิน 5MB)</p>
                            </div>
                            <div id="imagePreviewContainer" class="hidden">
                                <div class="relative inline-block">
                                    <img id="imagePreview" class="image-preview" alt="Preview">
                                    <button type="button" class="remove-image-btn" onclick="removeImage(event)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">คลิกเพื่อเปลี่ยนรูปภาพ</p>
                            </div>
                        </div>
                        <!-- Hidden input เก็บชื่อรูปเก่า -->
                        <input type="hidden" id="oldProjectImage" name="old_project_image" value="ชื่อไฟล์รูปเก่า.jpg" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="projectStatus">สถานะ</label>
                        <select id="projectStatus" name="project_status" class="form-input" required>
                            <option value="open">เปิดรับสมัคร</option>
                            <option value="closed">ปิดรับสมัคร</option>
                            <option value="in_progress">กำลังดำเนินการ</option>
                            <option value="completed">เสร็จสิ้นแล้ว</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="displayOrder">ลำดับการแสดงผล</label>
                        <select id="displayOrder" name="display_order" class="form-input">
                            <option value="1">1 - แสดงในหน้าแรก (อันดับ 1)</option>
                            <option value="2">2 - แสดงในหน้าแรก (อันดับ 2)</option>
                            <option value="3">3 - แสดงในหน้าแรก (อันดับ 3)</option>
                            <option value="4">4 - แสดงในหน้าแรก (อันดับ 4)</option>
                            <option value="other">ไม่แสดงในหน้าแรก</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">เฉพาะ 4 โครงการแรกเท่านั้นที่จะแสดงหมายเลขลำดับและปรากฏในหน้าหลัก</p>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded transition-colors">
                    ยกเลิก
                </button>
                <button type="button" onclick="saveProject()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                    บันทึก
                </button>
            </div>
        </div>
    </div>

    <script>
        // Store project data from PHP
        let trainingProjects = <?php echo json_encode($projects); ?>;

        // Function to handle image upload
        function handleImageUpload(input) {
            const file = input.files[0];
            if (!file) return;

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('ข้อผิดพลาด', 'กรุณาเลือกไฟล์รูปภาพ (JPG, PNG, GIF)', 'error');
                input.value = '';
                return;
            }

            // Validate file size (5MB)
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire('ข้อผิดพลาด', 'ขนาดไฟล์ใหญ่เกินไป กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 5MB', 'error');
                input.value = '';
                return;
            }

            // Create file reader
            const reader = new FileReader();
            reader.onload = function(e) {
                showImagePreview(e.target.result);
            };
            reader.readAsDataURL(file);
        }

        // Function to show image preview
        function showImagePreview(imageSrc) {
            document.getElementById('uploadPrompt').classList.add('hidden');
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
            document.getElementById('imagePreview').src = imageSrc;
        }

        // Function to hide image preview
        function hideImagePreview() {
            document.getElementById('uploadPrompt').classList.remove('hidden');
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('imagePreview').src = '';
        }

        // Function to remove image
        function removeImage(event) {
            event.stopPropagation();
            event.preventDefault();
            document.getElementById('imageUploadInput').value = '';
            hideImagePreview();
        }

        // Function to open add modal
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'เพิ่มโครงการใหม่';
            document.getElementById('projectForm').reset();
            document.getElementById('projectId').value = '';
            document.getElementById('currentImagePath').value = '';
            hideImagePreview();
            document.getElementById('displayOrder').value = 'other';
            document.getElementById('projectStatus').value = 'open';
            document.getElementById('editModal').classList.add('show');
        }


        function editProject(projectId) {
            console.log("🔧 เรียกฟังก์ชัน editProject ด้วย ID:", projectId);

            const project = trainingProjects.find(p => p.id == projectId);
            if (!project) {
                console.error("❌ ไม่พบ project:", projectId);
                return;
            }

            // ใส่ข้อมูลลง form
            document.getElementById('modalTitle').textContent = 'แก้ไขข้อมูลโครงการ';
            document.getElementById('projectId').value = project.id;
            console.log("✅ เซ็ต project_id:", project.id);

            document.getElementById('currentImagePath').value = project.image_path || '';
            document.getElementById('projectTitle').value = project.title;
            document.getElementById('projectDescription').value = project.description;
            document.getElementById('projectDate').value = project.training_date;
            document.getElementById('projectLocation').value = project.location;
            document.getElementById('projectStatus').value = project.project_status;

            // แก้ไขการตั้งค่า display_order
            const displayOrder = parseInt(project.display_order) || 0;
            if (displayOrder >= 1 && displayOrder <= 4) {
                document.getElementById('displayOrder').value = displayOrder;
            } else {
                document.getElementById('displayOrder').value = 'other';
            }

            // แสดงรูปภาพ preview
            if (project.image_path && project.image_path !== 'https://via.placeholder.com/600x300') {
                showImagePreview('../uploads/projects/' + project.image_path);
            } else {
                hideImagePreview();
            }

            // แสดง modal
            document.getElementById('editModal').classList.add('show');
        }

        // Function to close modal
        function closeModal() {
            document.getElementById('editModal').classList.remove('show');
        }


        function uploadImage(file, oldFilename = '') {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', 'projects');
            if (oldFilename) {
                formData.append('oldFilename', oldFilename);
            }

            return fetch('../functions/upload_image.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        return data.filename;
                    } else {
                        throw new Error(data.message || 'อัปโหลดภาพล้มเหลว');
                    }
                });
        }

        async function saveProject() {
            const form = document.getElementById('projectForm');
            const formData = new FormData(form);

            const imageFile = document.getElementById('imageUploadInput').files[0];
            const oldImage = document.getElementById('currentImagePath').value || '';

            if (imageFile) {
                try {
                    const newFilename = await uploadImage(imageFile, oldImage);
                    formData.set('project_image', newFilename);

                    const res = await fetch('../functions/admin/training_project.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();

                    if (data.success) {
                        Swal.fire('สำเร็จ', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('ข้อผิดพลาด', data.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('ข้อผิดพลาด', error.message, 'error');
                }
            } else {
                // ไม่มีภาพใหม่ ส่งข้อมูลปกติ
                fetch('../functions/admin/training_project.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('สำเร็จ', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('ข้อผิดพลาด', data.message, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้', 'error');
                    });
            }
        }



        // Function to delete project
        //      function deleteProject(projectId, imageName = '') {
        //     Swal.fire({
        //         title: 'คุณแน่ใจหรือไม่?',
        //         text: "คุณจะไม่สามารถย้อนกลับการกระทำนี้ได้!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#3085d6',
        //         confirmButtonText: 'ใช่, ลบเลย!',
        //         cancelButtonText: 'ยกเลิก'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             fetch('../functions/admin/training_project.php', {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/x-www-form-urlencoded',
        //                 },
        //                 body: new URLSearchParams({
        //                     action: 'delete',
        //                     project_id: projectId,
        //                     image_name: imageName // ✅ ส่งชื่อไฟล์ภาพไปด้วย
        //                 })
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 if (data.success) {
        //                     Swal.fire('ลบแล้ว!', data.message, 'success').then(() => {
        //                         location.reload();
        //                     });
        //                 } else {
        //                     Swal.fire('ข้อผิดพลาด', data.message, 'error');
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error:', error);
        //                 Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
        //             });
        //         }
        //     });
        // }
        function deleteProject(projectId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถย้อนกลับการกระทำนี้ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../functions/admin/training_project.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                action: 'delete',
                                project_id: projectId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('ลบแล้ว!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('ข้อผิดพลาด', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
                        });
                }
            });
        }


        // Setup drag and drop
        function setupDragAndDrop() {
            const container = document.getElementById('imageUploadContainer');

            container.addEventListener('dragover', function(e) {
                e.preventDefault();
                container.classList.add('dragover');
            });

            container.addEventListener('dragleave', function(e) {
                e.preventDefault();
                container.classList.remove('dragover');
            });

            container.addEventListener('drop', function(e) {
                e.preventDefault();
                container.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('imageUploadInput').files = files;
                    handleImageUpload(document.getElementById('imageUploadInput'));
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop();

            // Add animation to cards
            const cards = document.querySelectorAll('.training-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('visible');
                }, index * 100);
            });
        });
    </script>
</body>

</html>