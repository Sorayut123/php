<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบเลือกหมวดหมู่คำถาม</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * { font-family: 'Inter', sans-serif; }
        
        body { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }
        
        .main-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            margin: 1rem;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #ff6b35, #ff8e53);
            border-radius: 20px 20px 0 0;
        }
        
        .category-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        
        .category-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .category-card.disabled:hover {
            transform: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .category-icon {
            font-size: 2rem;
            color: #ff6b35;
            margin-bottom: 1rem;
        }
        
        .btn {
            background: linear-gradient(135deg, #ff6b35, #ff8e53);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }
        
        .btn-leave {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        .btn-leave:hover {
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            transform: scale(0.9);
            transition: all 0.3s ease;
        }
        
        .modal.show .modal-content {
            transform: scale(1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #ff6b35, #ff8e53);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 1rem;
            background: #f8fafc;
        }
        
        .message {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .message.own {
            flex-direction: row-reverse;
        }
        
        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b35, #ff8e53);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .message-content {
            max-width: 70%;
            padding: 0.75rem 1rem;
            border-radius: 16px;
            word-wrap: break-word;
        }
        
        .message-content.other {
            background: white;
            border: 1px solid #e2e8f0;
            color: #374151;
        }
        
        .message-content.own {
            background: linear-gradient(135deg, #ff6b35, #ff8e53);
            color: white;
        }
        
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ff6b35, #ff8e53);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .status-indicator {
            position: fixed;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 1000;
            opacity: 0;
            transform: translateX(-100%);
            transition: all 0.3s ease;
        }
        
        .status-indicator.show {
            opacity: 1;
            transform: translateX(0);
        }
        
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
        
        @media (max-width: 768px) {
            .main-content { margin: 0.5rem; }
            .header-gradient { border-radius: 16px 16px 0 0; }
        }
    </style>
</head>

<body>
        <div class="flex min-h-screen">
        <div class="flex-1">
            <div class="main-content animate-fade-in-up">
                <!-- Header -->
                <div class="header-gradient p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">
                                <i class="fas fa-comments mr-3"></i>
                                ระบบเลือกหมวดหมู่คำถาม
                            </h1>
                            <p class="text-white opacity-90">👋 สวัสดีคุณ Member1 กรุณาเลือกหมวดหมู่ที่คุณต้องการสอบถาม</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-white font-medium">Member1</span>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Category Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- IT Category -->
                        <div class="category-card p-6" id="category-IT" onclick="selectCategory('IT', 'ไอที')">
                            <div class="category-icon text-center mb-4">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">🧠 ไอที</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-user-tie text-orange-500"></i>
                                    <span>ผู้เชี่ยวชาญ: 3 คน</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-users text-orange-500"></i>
                                    <span>สมาชิก: 15 คน</span>
                                </div>
                            </div>
                        </div>

                        <!-- Law Category -->
                        <div class="category-card p-6" id="category-Law" onclick="selectCategory('Law', 'กฎหมาย')">
                            <div class="category-icon text-center mb-4">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">⚖️ กฎหมาย</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-user-tie text-orange-500"></i>
                                    <span>ผู้เชี่ยวชาญ: 2 คน</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-users text-orange-500"></i>
                                    <span>สมาชิก: 8 คน</span>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Category -->
                        <div class="category-card p-6" id="category-Medical" onclick="selectCategory('Medical', 'การแพทย์')">
                            <div class="category-icon text-center mb-4">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">🩺 การแพทย์</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-user-tie text-orange-500"></i>
                                    <span>ผู้เชี่ยวชาญ: 4 คน</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-users text-orange-500"></i>
                                    <span>สมาชิก: 12 คน</span>
                                </div>
                            </div>
                        </div>

                        <!-- Education Category -->
                        <div class="category-card p-6" id="category-Education" onclick="selectCategory('Education', 'การศึกษา')">
                            <div class="category-icon text-center mb-4">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">🎓 การศึกษา</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-user-tie text-orange-500"></i>
                                    <span>ผู้เชี่ยวชาญ: 1 คน</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-users text-orange-500"></i>
                                    <span>สมาชิก: 5 คน</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-orange-800 mb-4">📋 ข้อมูลการใช้งาน</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-orange-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span>เมื่อคุณเลือกหมวดหมู่ → ระบบจะพาคุณเข้าสู่ห้องแชทกลุ่มของหมวดนั้น</span>
                            </div>
                            <div class="flex items-center gap-3 text-orange-700">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span>ข้อความของคุณจะมองเห็นโดยผู้เชี่ยวชาญและสมาชิกในหมวดเดียวกัน</span>
                            </div>
                            <div class="flex items-center gap-3 text-orange-700">
                                <i class="fas fa-exclamation-circle text-yellow-500"></i>
                                <span>หลังจากสนทนาเสร็จแล้ว ต้องออกจากกลุ่มก่อนถึงจะสามารถเข้าร่วมกลุ่มอื่นได้</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center gap-4">
                        <button class="btn btn-secondary flex items-center gap-2" onclick="goHome()">
                            <i class="fas fa-home"></i>
                            กลับหน้าแรก
                        </button>
                        <button class="btn flex items-center gap-2" onclick="logout()">
                            <i class="fas fa-sign-out-alt"></i>
                            ออกจากระบบ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Indicator -->
    <div id="statusIndicator" class="status-indicator">
        <div class="flex items-center gap-2">
            <i class="fas fa-circle text-green-400"></i>
            <span id="statusText">พร้อมเลือกหมวดหมู่</span>
        </div>
    </div>

    <!-- Chat Modal -->
    <div id="chatModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="flex items-center gap-3">
                    <i id="modalCategoryIcon" class="fas fa-laptop-code text-2xl"></i>
                    <div>
                        <h3 id="modalCategoryName" class="text-xl font-bold">ห้องแชทกลุ่ม</h3>
                        <p class="text-sm opacity-90">พูดคุยกับผู้เชี่ยวชาญและสมาชิกในหมวด</p>
                    </div>
                </div>
<button class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-full transition-colors" onclick="closeChatModal()">
    <i class="fas fa-times"></i> <!-- เปลี่ยน icon เป็นกากบาท -->
</button>
            </div>

           

            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be populated here -->
            </div>

            <div class="p-4 bg-white border-t">
                <div class="flex gap-3 mb-3">
                    <input type="text" id="messageInput" placeholder="พิมพ์ข้อความของคุณ..." 
                           class="flex-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <button class="btn px-4 py-3" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="flex justify-center">
                    <button class="btn btn-leave flex items-center gap-2" onclick="leaveGroup()">
                        <i class="fas fa-sign-out-alt"></i>
                        ออกจากกลุ่ม
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <div class="flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <span id="toastMessage"></span>
        </div>
    </div>

    <script>
       // ระบบเลือกหมวดหมู่คำถาม - JavaScript
let currentCategory = null;
let isInChat = false;
let chatUsers = [];
let messages = [];
let messageId = 1;

// ข้อมูลจำลองผู้ใช้และผู้เชี่ยวชาญ
const categoryData = {
    'IT': {
        icon: 'fas fa-laptop-code',
        experts: ['Expert_IT1', 'Expert_IT2', 'Expert_IT3'],
        members: ['Member_IT1', 'Member_IT2', 'Member_IT3', 'Member_IT4', 'Member_IT5']
    },
    'Law': {
        icon: 'fas fa-balance-scale',
        experts: ['Expert_Law1', 'Expert_Law2'],
        members: ['Member_Law1', 'Member_Law2', 'Member_Law3']
    },
    'Medical': {
        icon: 'fas fa-stethoscope',
        experts: ['Expert_Med1', 'Expert_Med2', 'Expert_Med3', 'Expert_Med4'],
        members: ['Member_Med1', 'Member_Med2', 'Member_Med3', 'Member_Med4', 'Member_Med5', 'Member_Med6']
    },
    'Education': {
        icon: 'fas fa-graduation-cap',
        experts: ['Expert_Edu1'],
        members: ['Member_Edu1', 'Member_Edu2', 'Member_Edu3']
    }
};

// ข้อความต้อนรับเริ่มต้น
const welcomeMessages = {
    'IT': [
        { user: 'Expert_IT1', message: 'สวัสดีครับ! ยินดีต้อนรับสู่ห้องแชท IT มีปัญหาอะไรเกี่ยวกับเทคโนโลยีถามได้เลยครับ', isExpert: true },
        { user: 'Expert_IT2', message: 'พร้อมช่วยเหลือในเรื่องการพัฒนาเว็บไซต์และแอพพลิเคชั่นครับ', isExpert: true }
    ],
    'Law': [
        { user: 'Expert_Law1', message: 'สวัสดีครับ ยินดีให้คำปรึกษาทางกฎหมายครับ', isExpert: true },
        { user: 'Expert_Law2', message: 'มีคำถามด้านกฎหมายอะไรสามารถสอบถามได้เลยครับ', isExpert: true }
    ],
    'Medical': [
        { user: 'Expert_Med1', message: 'สวัสดีครับ ยินดีให้คำปรึกษาด้านการแพทย์ครับ', isExpert: true },
        { user: 'Expert_Med2', message: 'มีอาการหรือปัญหาสุขภาพอะไรปรึกษาได้ครับ', isExpert: true }
    ],
    'Education': [
        { user: 'Expert_Edu1', message: 'สวัสดีครับ ยินดีให้คำปรึกษาด้านการศึกษาครับ', isExpert: true }
    ]
};

// เริ่มต้นระบบเมื่อโหลดหน้า
document.addEventListener('DOMContentLoaded', function() {
    initializeSystem();
    setupEventListeners();
});

function closeChatModal() {
    const modal = document.getElementById("chatModal");
    modal.style.display = "none"; // หรือใช้ classList.add/remove แล้วแต่คุณใช้ระบบ modal แบบไหน

        // ✨ เพิ่มบรรทัดนี้ (ถ้าต้องการให้สามารถเปลี่ยนหมวดได้ทันที)
    isInChat = false;
}


function initializeSystem() {
    // แสดง status indicator
    showStatusIndicator('พร้อมเลือกหมวดหมู่');
    
    // ตั้งค่าเริ่มต้น
    currentCategory = null;
    isInChat = false;
    messages = [];
    
    // เปิดใช้งานทุก category
    enableAllCategories();
}

function setupEventListeners() {
    // Enter key สำหรับการส่งข้อความ
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // ปิด modal เมื่อคลิกนอกพื้นที่
    const chatModal = document.getElementById('chatModal');
    if (chatModal) {
        chatModal.addEventListener('click', function(e) {
            if (e.target === chatModal) {
                // ไม่ให้ปิด modal โดยการคลิกข้างนอก
                // เพื่อป้องกันการออกจากกลุ่มโดยไม่ตั้งใจ
            }
        });
    }
}

function selectCategory(categoryId, categoryName) {
    if (isInChat) {
        showToast('คุณอยู่ในห้องแชทอยู่แล้ว กรุณาออกจากกลุ่มก่อน');
        return;
    }

    currentCategory = categoryId;
    
    // อัพเดท UI
    disableAllCategories();
    showStatusIndicator(`เข้าร่วมกลุ่ม ${categoryName}`);
    
    // เปิด modal แชท
    openChatModal(categoryId, categoryName);
    
    // จำลองการเข้าร่วมกลุ่ม
    joinGroup(categoryId);
}

function openChatModal(categoryId, categoryName) {
    const modal = document.getElementById('chatModal');
    const modalCategoryIcon = document.getElementById('modalCategoryIcon');
    const modalCategoryName = document.getElementById('modalCategoryName');
    
    // ตั้งค่าไอคอนและชื่อ
    modalCategoryIcon.className = categoryData[categoryId].icon + ' text-2xl';
    modalCategoryName.textContent = `ห้องแชทกลุ่ม ${categoryName}`;
    
    // แสดง modal
    modal.classList.add('show');
    isInChat = true;
    
    // โฟกัสที่ input
    setTimeout(() => {
        document.getElementById('messageInput').focus();
    }, 300);
}

function joinGroup(categoryId) {
    // รีเซ็ตข้อความ
    messages = [];
    
    // สร้างรายชื่อผู้ใช้ออนไลน์
    const data = categoryData[categoryId];
    chatUsers = [...data.experts, ...data.members];
    
    // เพิ่มผู้ใช้ปัจจุบัน
    if (!chatUsers.includes('Member1')) {
        chatUsers.unshift('Member1');
    }
    
    // แสดงรายชื่อผู้ใช้
    updateUserList();
    
    // เพิ่มข้อความต้อนรับ
    if (welcomeMessages[categoryId]) {
        welcomeMessages[categoryId].forEach(msg => {
            addMessage(msg.user, msg.message, msg.isExpert);
        });
    }
    
    // ข้อความต้อนรับสำหรับผู้ใช้ใหม่
    setTimeout(() => {
        addMessage('System', `${getCurrentUserName()} เข้าร่วมกลุ่มแล้ว`, false, true);
    }, 1000);
    
    showToast(`เข้าร่วมกลุ่ม ${categoryId} สำเร็จ`);
}

function updateUserList() {
    const userList = document.getElementById('userList');
    const onlineCount = document.getElementById('onlineCount');
    
    userList.innerHTML = '';
    onlineCount.textContent = chatUsers.length;
    
    chatUsers.forEach(user => {
        const userElement = document.createElement('div');
        userElement.className = 'flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full text-xs';
        
        const isExpert = user.includes('Expert');
        const isCurrentUser = user === 'Member1';
        
        userElement.innerHTML = `
            <i class="fas fa-circle text-green-500"></i>
            <span class="${isExpert ? 'font-bold text-orange-600' : ''} ${isCurrentUser ? 'text-blue-600' : ''}">
                ${isExpert ? '👨‍💼' : '👤'} ${user}
            </span>
        `;
        
        userList.appendChild(userElement);
    });
}

function addMessage(user, message, isExpert = false, isSystem = false) {
    const timestamp = new Date();
    const messageObj = {
        id: messageId++,
        user: user,
        message: message,
        timestamp: timestamp,
        isExpert: isExpert,
        isSystem: isSystem,
        isOwn: user === 'Member1'
    };
    
    messages.push(messageObj);
    displayMessage(messageObj);
    
    // เลื่อนไปที่ข้อความล่าสุด
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function displayMessage(messageObj) {
    const chatMessages = document.getElementById('chatMessages');
    const messageElement = document.createElement('div');
    
    if (messageObj.isSystem) {
        messageElement.className = 'text-center text-gray-500 text-sm mb-2';
        messageElement.innerHTML = `
            <i class="fas fa-info-circle"></i>
            ${messageObj.message}
        `;
    } else {
        messageElement.className = `message ${messageObj.isOwn ? 'own' : ''}`;
        
        const avatar = messageObj.isOwn ? 'คุณ' : 
                      messageObj.isExpert ? '👨‍💼' : '👤';
        
        const timeStr = messageObj.timestamp.toLocaleTimeString('th-TH', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        messageElement.innerHTML = `
            <div class="message-avatar">${avatar}</div>
            <div class="message-content ${messageObj.isOwn ? 'own' : 'other'}">
                <div class="text-xs opacity-75 mb-1">
                    ${messageObj.user} • ${timeStr}
                </div>
                <div>${messageObj.message}</div>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageElement);
}

function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // ส่งข้อความของผู้ใช้
    addMessage('Member1', message, false);
    messageInput.value = '';
    
    // จำลองการตอบกลับจากผู้เชี่ยวชาญ
    simulateExpertResponse(message);
}

function simulateExpertResponse(userMessage) {
    setTimeout(() => {
        const data = categoryData[currentCategory];
        const randomExpert = data.experts[Math.floor(Math.random() * data.experts.length)];
        
        let response = '';
        
        // จำลองการตอบกลับตามหมวดหมู่
        if (currentCategory === 'IT') {
            const itResponses = [
                'ปัญหานี้น่าสนใจครับ ลองตรวจสอบ log files ดูก่อน',
                'แนะนำให้ใช้ version control อย่าง Git ครับ',
                'ลองอัพเดท dependencies ให้เป็นเวอร์ชั่นล่าสุดดูครับ',
                'เรื่องนี้เกี่ยวกับ database optimization ใช่ไหมครับ?'
            ];
            response = itResponses[Math.floor(Math.random() * itResponses.length)];
        } else if (currentCategory === 'Law') {
            const lawResponses = [
                'ตามกฎหมายแล้ว คุณควรปรึกษาทนายความโดยตรงครับ',
                'กรณีนี้อาจต้องยื่นเรื่องต่อศาลครับ',
                'ข้อกฎหมายที่เกี่ยวข้องคือ มาตรา... ของพระราชบัญญัติ...',
                'แนะนำให้เก็บหลักฐานไว้ให้ครบถ้วนครับ'
            ];
            response = lawResponses[Math.floor(Math.random() * lawResponses.length)];
        } else if (currentCategory === 'Medical') {
            const medResponses = [
                'อาการที่คุณบรรยายควรไปพบแพทย์เพื่อตรวจอย่างละเอียดครับ',
                'แนะนำให้ดื่มน้ำเปล่าให้เพียงพอและพักผ่อนให้เพียงพอครับ',
                'ยานี้อาจมีผลข้างเคียง ควรปรึกษาเภสัชกรครับ',
                'การออกกำลังกายสม่ำเสมอจะช่วยได้มากครับ'
            ];
            response = medResponses[Math.floor(Math.random() * medResponses.length)];
        } else if (currentCategory === 'Education') {
            const eduResponses = [
                'วิธีเรียนที่มีประสิทธิภาพคือการทำ mind map ครับ',
                'แนะนำให้หาแหล่งเรียนรู้ออนไลน์เพิ่มเติมครับ',
                'การฝึกทำข้อสอบจะช่วยเพิ่มความเชื่อมั่นครับ',
                'ควรตั้งเป้าหมายการเรียนให้ชัดเจนครับ'
            ];
            response = eduResponses[Math.floor(Math.random() * eduResponses.length)];
        }
        
        addMessage(randomExpert, response, true);
    }, 1000 + Math.random() * 2000);
}

function leaveGroup() {
    Swal.fire({
        title: 'ยืนยันการออกจากกลุ่ม',
        text: 'คุณต้องการออกจากกลุ่มนี้หรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ออกจากกลุ่ม',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // ปิด modal
            const modal = document.getElementById('chatModal');
            modal.classList.remove('show');
            
            // รีเซ็ตสถานะ
            currentCategory = null;
            isInChat = false;
            messages = [];
            chatUsers = [];
            
            // เปิดใช้งาน categories อีกครั้ง
            enableAllCategories();
            showStatusIndicator('พร้อมเลือกหมวดหมู่');
            
            // ล้างข้อความในแชท
            document.getElementById('chatMessages').innerHTML = '';
            document.getElementById('messageInput').value = '';
            
            showToast('ออกจากกลุ่มสำเร็จ');
        }
    });
}

function enableAllCategories() {
    const categories = document.querySelectorAll('.category-card');
    categories.forEach(card => {
        card.classList.remove('disabled');
    });
}

function disableAllCategories() {
    const categories = document.querySelectorAll('.category-card');
    categories.forEach(card => {
        card.classList.add('disabled');
    });
}

function showStatusIndicator(message) {
    const indicator = document.getElementById('statusIndicator');
    const statusText = document.getElementById('statusText');
    
    statusText.textContent = message;
    indicator.classList.add('show');
    
    setTimeout(() => {
        indicator.classList.remove('show');
    }, 3000);
}

function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function getCurrentUserName() {
    return 'Member1';
}

function goHome() {
    if (isInChat) {
        showToast('กรุณาออกจากกลุ่มก่อนที่จะกลับหน้าแรก');
        return;
    }
    
    Swal.fire({
        title: 'กลับหน้าแรก',
        text: 'คุณต้องการกลับไปยังหน้าแรกหรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6b7280',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'กลับหน้าแรก',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // จำลองการกลับหน้าแรก
            showToast('กำลังกลับหน้าแรก...');
            setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        }
    });
}

function logout() {
    if (isInChat) {
        showToast('กรุณาออกจากกลุ่มก่อนที่จะออกจากระบบ');
        return;
    }
    
    Swal.fire({
        title: 'ออกจากระบบ',
        text: 'คุณต้องการออกจากระบบหรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ออกจากระบบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            showToast('กำลังออกจากระบบ...');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1000);
        }
    });
}

// ป้องกันการรีเฟรชหน้าเมื่ออยู่ในแชท
window.addEventListener('beforeunload', function(e) {
    if (isInChat) {
        e.preventDefault();
        e.returnValue = 'คุณอยู่ในห้องแชทอยู่ คุณต้องการออกจากหน้านี้หรือไม่?';
    }
});

// ฟังก์ชันสำหรับจำลองผู้ใช้อื่นเข้า-ออกจากกลุ่ม
function simulateUserActivity() {
    if (!isInChat) return;
    
    // จำลองผู้ใช้ใหม่เข้าร่วม
    if (Math.random() < 0.3) {
        const newUser = `Member_${Date.now()}`;
        if (!chatUsers.includes(newUser)) {
            chatUsers.push(newUser);
            updateUserList();
            addMessage('System', `${newUser} เข้าร่วมกลุ่มแล้ว`, false, true);
        }
    }
    
    // จำลองผู้ใช้ออกจากกลุ่ม
    if (Math.random() < 0.2 && chatUsers.length > 3) {
        const randomIndex = Math.floor(Math.random() * chatUsers.length);
        const userToLeave = chatUsers[randomIndex];
        
        if (userToLeave !== 'Member1' && !userToLeave.includes('Expert')) {
            chatUsers.splice(randomIndex, 1);
            updateUserList();
            addMessage('System', `${userToLeave} ออกจากกลุ่มแล้ว`, false, true);
        }
    }
}

// จำลองกิจกรรมผู้ใช้ทุก 30 วินาที
setInterval(simulateUserActivity, 30000);

// เพิ่มความสมจริงด้วยการสร้างข้อความสุ่มจากสมาชิก
function simulateRandomMemberMessage() {
    if (!isInChat) return;
    
    const members = chatUsers.filter(user => 
        !user.includes('Expert') && 
        user !== 'Member1' && 
        user !== 'System'
    );
    
    if (members.length > 0 && Math.random() < 0.1) {
        const randomMember = members[Math.floor(Math.random() * members.length)];
        const memberMessages = [
            'ขอบคุณสำหรับคำแนะนำครับ',
            'ผมมีปัญหาคล้ายๆ กันเหมือนกันครับ',
            'วิธีนี้ดีมากครับ จะลองไปทำดู',
            'มีข้อมูลเพิ่มเติมไหมครับ',
            'ขอบคุณผู้เชี่ยวชาญมากครับ'
        ];
        
        const randomMessage = memberMessages[Math.floor(Math.random() * memberMessages.length)];
        addMessage(randomMember, randomMessage, false);
    }
}

// จำลองข้อความจากสมาชิกทุก 45 วินาที
setInterval(simulateRandomMemberMessage, 45000);
    </script>
</body>
</html>