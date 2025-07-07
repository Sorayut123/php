<?php

// session_start();
require_once '../config/db.php';
require_once '../functions/expert/account_expert.php';

// // ตอนนี้ตัวแปร $user จะพร้อมใช้งาน
$fullName = $user['FullName'];
$username = $user['Username'];
$email = $user['Email'];
$birthday = $user['BirthDate'];
$phone = $user['Telephone'];
$gender = $user['Gender'];
$occupation = $user['Occupation'];
$address = $user['Address'];
$district = $user['District'];
$subDistrict = $user['SubDistrict'];
$province = $user['Province'];
$zipCode = $user['ZipCode'];
$roleId = $user['role_id'];

// // ถ้าได้รูปแบบอื่น เช่น '25/06/2025' หรือ 'June 25, 2025'
// $birthday = date('Y-m-d', strtotime($user['BirthDate']));
$roleMap = [
    1 => 'ผู้ดูแลระบบ',
    2 => 'ผู้เชี่ยวชาญ',
    3 => 'สมาชิกทั่วไป'
];

// ตรวจสอบว่า $roleId ถูกกำหนดแล้วหรือยัง
$roleName = isset($roleId) ? ($roleMap[$roleId] ?? 'ไม่ทราบสิทธิ์') : 'ไม่ทราบสิทธิ์';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Account Settings</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        /* Main Content */
        .main-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            margin: 1rem;
        }

        /* Header */
        .header-gradient {
            background-color: #ff6b35;
            border-radius: 20px 20px 0 0;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        /* Form Styles */
        .form-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
            background: white;
        }

        .form-input:disabled {
            background: #f1f5f9;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Profile Picture */
        .profile-upload {
            position: relative;
            display: inline-block;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .profile-upload:hover .upload-overlay {
            opacity: 1;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
        }

        /* Profile Image */
        .profile-img-header {
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Grid Layout */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-grid-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Custom Select */
        .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27m6 8 4 4 4-4%27/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }

        /* Read-only badge */
        .readonly-badge {
            background: #f59e0b;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            margin-left: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="flex min-h-screen">
        <?php include '../includes/sidebarExpert.php'; ?>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="main-content animate-fade-in-up">
                <!-- Header -->
                <div class="header-gradient p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">
                                <i class="fas fa-user-cog mr-3"></i>
                                Account Settings
                            </h1>
                            <p class="text-slate-300">จัดการข้อมูลส่วนตัวและการตั้งค่าบัญชี</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-white font-medium"><?= htmlspecialchars($fullName) ?></span>
                            <img src="https://via.placeholder.com/40" class="rounded-full w-12 h-12 profile-img-header" alt="Profile">
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <form id="form" action="../functions/expert/account_expert.php" method="post" enctype="multipart/form-data" class="space-y-6">

                        <!-- Profile Section -->
                        <div class="form-section">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-user-circle text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-2xl font-bold text-gray-800">ข้อมูลโปรไฟล์</h2>
                            </div>

                            <div class="flex flex-col md:flex-row items-start gap-8">
                                                            <div class="profile-upload relative">
                                    <!-- รูปปัจจุบัน -->
                                    <img src="<?= !empty($user['ProfileImage']) ? '../uploads/profileExpert/' . htmlspecialchars($user['ProfileImage']) : 'https://via.placeholder.com/120' ?>"
                                        class="profile-img" id="profilePreview" alt="Profile">

                                    <!-- รูปซ้อนกล้อง -->
                                    <div class="upload-overlay">
                                        <i class="fas fa-camera text-white text-2xl"></i>
                                    </div>

                                    <!-- เลือกรูปใหม่ -->
                                    <input type="file" name="profileInput" accept="image/*" id="profileInput"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                    <!-- ซ่อนชื่อไฟล์เดิมไว้ (ไว้ใช้ลบ/เปรียบเทียบ) -->
                                    <input type="hidden" id="currentProfileImage" value="<?= htmlspecialchars($user['ProfileImage'] ?? '') ?>">
                                </div>

                                <!-- ซ่อนชื่อไฟล์ใหม่ไว้ส่งไปบันทึกในฐานข้อมูล -->
                                <input type="hidden" name="newProfileImage" id="newProfileImage" value="<?= htmlspecialchars($user['ProfileImage'] ?? '') ?>">

                                <div class="flex-1 space-y-4">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800 mb-2">คำแนะนำการอัปโหลดรูปภาพ</p>
                                        <ul class="text-sm text-gray-600 space-y-1">
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>ขนาดไฟล์ไม่เกิน 2MB</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>รูปแบบไฟล์: JPG, PNG, GIF</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>ขนาดแนะนำ: 400x400 พิกเซล</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="form-section">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-id-card text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-2xl font-bold text-gray-800">ข้อมูลส่วนตัว</h2>
                            </div>

                            <div class="form-grid">
                                <div>
                                    <label class="form-label"><i class="fas fa-user text-gray-500"></i>ชื่อ-นามสกุล</label>
                                    <input name="FullName" class="form-input w-full" type="text"
                                        value="<?= htmlspecialchars($fullName) ?>" placeholder="กรุณากรอกชื่อ-นามสกุล">
                                </div>

                                <div>
                                    <label class="form-label"><i class="fas fa-at text-gray-500"></i>Username</label>
                                    <input name="Username" class="form-input w-full" type="text"
                                        value="<?= htmlspecialchars($username) ?>" placeholder="กรุณากรอก Username">
                                </div>

                                <div>
                                    <label class="form-label"><i class="fas fa-envelope text-gray-500"></i>Email</label>
                                    <input name="Email" class="form-input w-full" type="email"
                                        value="<?= htmlspecialchars($email) ?>" placeholder="กรุณากรอก Email">
                                </div>

                                <div>
                                    <label class="form-label"><i class="fas fa-phone text-gray-500"></i>เบอร์โทรศัพท์</label>
                                    <input name="Phone" class="form-input w-full" type="text"
                                        value="<?= htmlspecialchars($phone) ?>" placeholder="กรุณากรอกเบอร์โทรศัพท์">
                                </div>

                                <div>
                                    <label class="form-label"><i class="fas fa-birthday-cake text-gray-500"></i>วันเกิด</label>
                                    <input name="Birthday" class="form-input w-full" type="date"
                                        value="<?= htmlspecialchars($birthday ?? '') ?>">
                                </div>

                                <div>
                                    <label class="form-label"><i class="fas fa-venus-mars text-gray-500"></i>เพศ</label>
                                    <select name="Gender" class="form-input custom-select w-full">
                                        <option value="ชาย" <?= $gender === 'ชาย' ? 'selected' : '' ?>>ชาย</option>
                                        <option value="หญิง" <?= $gender === 'หญิง' ? 'selected' : '' ?>>หญิง</option>
                                        <option value="อื่น ๆ" <?= $gender === 'อื่น ๆ' ? 'selected' : '' ?>>อื่น ๆ</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label"><i class="fas fa-briefcase text-gray-500"></i>อาชีพ</label>
                                    <input name="Occupation" class="form-input w-full" type="text"
                                        value="<?= htmlspecialchars($occupation ?? '') ?>" placeholder="กรุณากรอกอาชีพ">
                                </div>

                                <div>
                                    <div>
                                        <label class="form-label">
                                            <i class="fas fa-user-shield text-gray-500"></i>สิทธิ์การใช้งาน
                                            <span class="readonly-badge">แก้ไม่ได้</span>
                                        </label>
                                        <input name="role_id" class="form-input w-full" type="text" disabled
                                            value="<?= htmlspecialchars($roleName) ?>" placeholder="สิทธิ์การใช้งาน">
                                    </div>

                                </div>

                                <div>
                                    <label class="form-label"><i class="fas fa-lock text-gray-500"></i>รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)</label>
                                    <input type="password" name="Password" id="Password" class="form-input w-full"
                                        placeholder="กรุณากรอกรหัสผ่านอย่างน้อย 8 ตัวอักษร">
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="form-section">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-map-marker-alt text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-2xl font-bold text-gray-800">ข้อมูลที่อยู่</h2>
                            </div>

                            <div class="space-y-4">
                                <div class="form-grid-full">
                                    <div>
                                        <label class="form-label"><i class="fas fa-home text-gray-500"></i>ที่อยู่</label>
                                        <textarea name="Address" class="form-input w-full" rows="3"
                                            placeholder="กรุณากรอกที่อยู่"><?= htmlspecialchars($address ?? '') ?></textarea>
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div>
                                        <label class="form-label"><i class="fas fa-building text-gray-500"></i>อำเภอ/เขต</label>
                                        <input name="District" class="form-input w-full" type="text"
                                            value="<?= htmlspecialchars($district ?? '') ?>" placeholder="กรุณากรอกอำเภอ/เขต">
                                    </div>

                                    <div>
                                        <label class="form-label"><i class="fas fa-map text-gray-500"></i>ตำบล/แขวง</label>
                                        <input name="SubDistrict" class="form-input w-full" type="text"
                                            value="<?= htmlspecialchars($subDistrict ?? '') ?>" placeholder="กรุณากรอกตำบล/แขวง">
                                    </div>

                                    <div>
                                        <label class="form-label"><i class="fas fa-flag text-gray-500"></i>จังหวัด</label>
                                        <input name="Province" class="form-input w-full" type="text"
                                            value="<?= htmlspecialchars($province ?? '') ?>" placeholder="กรุณากรอกจังหวัด">
                                    </div>

                                    <div>
                                        <label class="form-label"><i class="fas fa-mail-bulk text-gray-500"></i>รหัสไปรษณีย์</label>
                                        <input name="ZipCode" class="form-input w-full" type="text"
                                            value="<?= htmlspecialchars($zipCode ?? '') ?>" placeholder="กรุณากรอกรหัสไปรษณีย์">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <button type="button" class="btn-secondary" onclick="window.location.reload();">
                                <i class="fas fa-times mr-2"></i>ยกเลิก
                            </button>

                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedFile = null;

 
document.getElementById('form').addEventListener('submit', function(e) {
    e.preventDefault();

    const password = document.getElementById('Password').value.trim();

    if (password !== '' && password.length < 8) {
        Swal.fire({
            icon: 'warning',
            title: 'รหัสผ่านสั้นเกินไป',
            text: 'กรุณากรอกรหัสผ่านอย่างน้อย 8 ตัวอักษร',
        });
        return;
    }

    Swal.fire({
        title: 'ยืนยันการแก้ไข?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ใช่, แก้ไขเลย',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            uploadAndSubmit();
        }
    });
});



document.getElementById('profileInput').addEventListener('change', function(e) {
    selectedFile = e.target.files[0];
    if (selectedFile) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('profilePreview').src = e.target.result;
        reader.readAsDataURL(selectedFile);
    }
});

async function uploadAndSubmit() {
    if (selectedFile) {
        const formData = new FormData();
        formData.append("file", selectedFile);
        formData.append("folder", "profileExpert");

        const oldFilename = document.getElementById('currentProfileImage').value;
        if (oldFilename) {
            formData.append("oldFilename", oldFilename);
        }

        try {
            const res = await fetch("../functions/upload_image.php", {
                method: "POST",
                body: formData
            });

            const data = await res.json();

            if (data.status === "success") {
                document.getElementById('newProfileImage').value = data.filename;
                document.getElementById('form').submit();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'อัปโหลดไม่สำเร็จ',
                    text: data.message
                });
            }

        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: err.message || 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้'
            });
        }
    } else {
        document.getElementById('form').submit();
    }
}
        // Animation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.form-section');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    section.style.transition = 'all 0.6s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>

</html>