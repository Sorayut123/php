<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>แหล่งการเรียนรู้</title>
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

    /* Sidebar Styles */

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

    .video-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border: 1px solid #f1f5f9;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .video-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    /* Profile Image */
    .profile-img {
      border: 3px solid white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-slide-in {
      animation: slideInUp 0.4s ease-out;
    }

    /* Video iframe styles */
    .video-container {
      position: relative;
      width: 100%;
      height: 200px;
      background: #f1f5f9;
      border-radius: 12px 12px 0 0;
      overflow: hidden;
    }

    .video-container iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    /* View Only Badge */
    .view-only-badge {
      background: linear-gradient(135deg, #64748b 0%, #475569 100%);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: #64748b;
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }
  </style>
</head>

<body>
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <?php include '../includes/sidebarExpert.php'; ?>

    <!-- Main Content -->
    <div class="flex-1">
      <div class="main-content animate-fade-in-up">
        <!-- Header -->
        <div class="header-gradient p-6">
          <div class="flex justify-between items-center">
            <div>
              <h1 class="text-3xl font-bold text-white mb-2">
                <i class="fas fa-book-open mr-3"></i>
                แหล่งการเรียนรู้
              </h1>
              <p class="text-white">ดูวิดีโอและเนื้อหาการเรียนรู้ทั้งหมด</p>
            </div>
            <div class="flex items-center space-x-4">
         
              <span class="text-white font-medium">Admin Name</span>
              <img src="https://via.placeholder.com/40" class="rounded-full w-12 h-12 profile-img" alt="Profile">
            </div>
          </div>
        </div>

        <div class="p-8">
          <!-- Header Section -->
          <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
              <i class="fas fa-video text-blue-500 text-2xl mr-3"></i>
              <h2 class="text-2xl font-bold text-gray-800">วิดีโอการเรียนรู้ทั้งหมด</h2>
            </div>
            <!-- ปิดการใช้งานปุ่มเพิ่ม -->
          
          </div>

          <!-- Learning Resources Grid -->
          <div id="videoGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- วิดีโอจะถูกเพิ่มที่นี่โดย JS -->
          </div>

          <!-- Empty State -->
          <div id="emptyState" class="empty-state hidden">
            <i class="fas fa-video"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">ยังไม่มีวิดีโอในระบบ</h3>
            <p class="text-gray-500">ไม่มีวิดีโอการเรียนรู้ให้แสดง</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // ฟังก์ชันโหลดวิดีโอ (ปิดการใช้งานปุ่มแก้ไขและลบ)
    async function loadVideos() {
      try {
        const res = await fetch('../functions/expert/leaning_expert.php');
        if (!res.ok) throw new Error('ไม่สามารถดึงข้อมูลวิดีโอได้');
        const videos = await res.json();

        const videoGrid = document.getElementById('videoGrid');
        const emptyState = document.getElementById('emptyState');
        
        videoGrid.innerHTML = '';

        if (videos.length === 0) {
          emptyState.classList.remove('hidden');
          return;
        }

        emptyState.classList.add('hidden');

        videos.forEach(video => {
          let videoEmbed = '';

          if (video.video_type === 'youtube' && video.youtube_url) {
            const videoId = extractYouTubeID(video.youtube_url);
            if (videoId) {
              videoEmbed = `<iframe src="https://www.youtube.com/embed/${videoId}" allowfullscreen class="w-full h-48"></iframe>`;
            }
          } else if (video.video_type === 'upload' && video.file_path) {
            videoEmbed = `<video controls class="w-full h-48">
                        <source src="${video.file_path}" type="video/mp4">
                        เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                      </video>`;
          }

          // สร้าง card แต่ไม่มีปุ่มแก้ไขและลบ
          const cardHtml = `
            <div class="video-card animate-slide-in border rounded-lg overflow-hidden shadow-md">
              <div class="video-container">
                ${videoEmbed}
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">${escapeHtml(video.title)}</h3>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">${escapeHtml(video.description)}</p>
                <div class="flex justify-end">
                  <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                    <i class="fas fa-eye mr-1"></i>
                    View Only
                  </div>
                </div>
              </div>
            </div>`;

          videoGrid.insertAdjacentHTML('beforeend', cardHtml);
        });
      } catch (error) {
        console.error('Error loading videos:', error);
        // แสดงข้อความผิดพลาด
        const videoGrid = document.getElementById('videoGrid');
        videoGrid.innerHTML = `
          <div class="col-span-full text-center py-8">
            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">เกิดข้อผิดพลาด</h3>
            <p class="text-gray-500">ไม่สามารถโหลดข้อมูลวิดีโอได้</p>
          </div>
        `;
      }
    }

    // ฟังก์ชันช่วยดึง videoId จาก URL YouTube
    function extractYouTubeID(url) {
      const regExp = /^.*(?:youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
      const match = url.match(regExp);
      return (match && match[1].length === 11) ? match[1] : null;
    }

    // ฟังก์ชันช่วยป้องกัน XSS
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }

    // โหลดวิดีโอเมื่อเปิดหน้า
    window.addEventListener('DOMContentLoaded', loadVideos);

    // เพิ่ม animation delay ให้กับการ์ดวิดีโอ
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(() => {
        const videoCards = document.querySelectorAll('.video-card');
        videoCards.forEach((card, index) => {
          card.style.animationDelay = `${index * 0.1}s`;
        });
      }, 100);
    });
  </script>
</body>

</html>