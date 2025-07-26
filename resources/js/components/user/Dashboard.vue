<template>
    <section class="bg-gray-50 min-h-screen text-gray-800 font-sans">
        <div class="max-w-6xl mx-auto px-6 pt-24">

            <div class="mb-10 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-purple-800 flex items-center gap-2">
                        🚀 Dashboard Kamu <span class="text-lg bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full">Gen
                            Z</span>
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Halo, <span class="font-semibold text-purple-700">{{ userName }}</span>!
                        <span class="ml-1">👋</span>
                        {{ purchasedCourses.length > 0 ? 'Siap upgrade skill hari ini? Yuk cek progres dan tantangan barumu!' : 'Yuk mulai perjalanan coding kamu dengan membeli course pertama!' }}
                    </p>
                </div>
            </div>

            <!-- Section Course yang Sudah Dibeli -->
            <div v-if="purchasedCourses.length > 0" class="mb-10">
                <h2 class="text-2xl font-bold text-purple-800 mb-6 flex items-center gap-2">
                    📚 Course yang Sudah Kamu Beli
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="course in purchasedCourses"
                        :key="course.id"
                        class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow"
                    >
                        <div class="flex items-start gap-4">
                            <img
                                :src="getCourseImageUrl(course.image_url)"
                                :alt="course.title"
                                class="w-16 h-16 rounded-lg object-cover border-2 border-purple-200"
                            >
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-purple-800 mb-2">{{ course.title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Dibeli: {{ formatDate(course.purchased_at) }}
                                </p>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                                        ✓ Sudah Dibeli
                                    </span>
                                </div>
                                <a
                                    :href="'/course/' + course.id"
                                    class="inline-block mt-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition text-sm"
                                >
                                    Mulai Belajar 🚀
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section jika belum ada course yang dibeli -->
            <div v-else class="mb-10">
                <div class="bg-gradient-to-r from-purple-100 to-indigo-100 rounded-lg p-8 text-center">
                    <span class="text-6xl mb-4 block">📚</span>
                    <h2 class="text-2xl font-bold text-purple-800 mb-4">Belum Ada Course yang Dibeli</h2>
                    <p class="text-gray-600 mb-6">Yuk mulai perjalanan coding kamu! Pilih course yang sesuai dengan minat dan tujuan kariermu.</p>
                    <a href="/course" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-6 py-3 rounded-lg hover:scale-105 transition">
                        Lihat Course Available 🚀
                    </a>
                </div>
            </div>

            <!-- Notifikasi jika ada pembelian baru -->
            <div v-if="showPurchaseNotification" class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">🎉</span>
                    <div>
                        <p class="font-bold text-green-800">Selamat! Pembelian Anda berhasil!</p>
                        <p class="text-green-700">{{ latestPurchasedCourse }} sudah siap untuk dipelajari.</p>
                    </div>
                    <button
                        @click="dismissNotification"
                        class="ml-auto text-green-600 hover:text-green-800 font-bold"
                    >
                        ✕
                    </button>
                </div>
            </div>

            <!-- Dashboard Cards - Hanya tampil jika ada course yang dibeli -->
            <div v-if="purchasedCourses.length > 0" class="grid md:grid-cols-3 gap-6 mb-10">
                <!-- Program Aktif -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-700 flex items-center gap-2">🔥 Program Aktif</h3>
                    <div v-if="activeProgram">
                        <a :href="'/course/' + activeProgram.id" class="block">
                            <h4 class="text-xl font-bold text-purple-800 mt-2 hover:text-purple-600 transition">
                                {{ activeProgram.title }}
                            </h4>
                        </a>
                        <p class="text-sm text-gray-500 mt-1">
                            Dimulai: {{ formatDate(activeProgram.purchased_at) }}
                        </p>
                        <span class="inline-block mt-2 bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">On Fire!</span>
                    </div>
                    <div v-else>
                        <p class="text-lg text-gray-600 mt-2">Belum ada program aktif</p>
                        <a href="/course" class="text-sm text-purple-600 hover:underline">Pilih Course</a>
                    </div>
                </div>

                <!-- Progres Belajar -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-700 flex items-center gap-2">📈 Progres Belajar</h3>
                    <div class="w-full bg-gray-200 h-4 rounded-full mt-2 relative overflow-hidden">
                        <div id="progressBar"
                            class="bg-gradient-to-r from-purple-500 to-indigo-500 h-4 rounded-full transition-all duration-700"
                            :style="{ width: progress + '%' }"></div>
                        <span id="progressPercent"
                            class="absolute right-3 top-0 text-xs font-bold text-purple-700 h-4 flex items-center">{{ progress }}%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                        <span id="progressText">{{ modulSelesai }} dari {{ totalModul }} modul selesai</span>
                        <span class="ml-1">🎯</span>
                    </p>
                    <button @click="increaseProgress"
                        class="mt-3 px-4 py-2 bg-purple-600 text-white rounded-lg font-bold hover:bg-purple-700 transition">
                        Tambah Progress 🚀
                    </button>
                </div>

                <!-- Sesi Selanjutnya -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-700 flex items-center gap-2">⏰ Sesi Selanjutnya</h3>
                    <div v-if="activeProgram">
                        <p class="text-base mt-2 font-bold">{{ nextSessionDate }}</p>
                        <p class="text-sm text-gray-500">
                            Course: <span class="font-semibold text-indigo-700">{{ activeProgram.title }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Bareng: <span class="font-semibold text-indigo-700">Mentor Tim</span></p>
                        <span class="inline-block mt-2 bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Jangan Telat!</span>
                    </div>
                    <div v-else>
                        <p class="text-sm text-gray-600 mt-2">Belum ada sesi terjadwal</p>
                        <p class="text-xs text-gray-400">Beli course untuk memulai sesi belajar</p>
                    </div>
                </div>
            </div>

            <!-- Stats banner - hanya tampil jika ada course -->
            <div v-if="purchasedCourses.length > 0" class="bg-gradient-to-r from-indigo-100 to-purple-100 rounded-lg p-4 text-center mb-10">
                <span class="text-purple-800 font-bold">👨‍💻 12.000+</span> Gen Z sudah belajar bareng Dunia Coding!
                <span class="ml-2 text-green-600 font-semibold">#NoMoreSkip</span>
            </div>

            <!-- Learning Progress Cards - hanya tampil jika ada course -->
            <div v-if="purchasedCourses.length > 0" class="grid md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-purple-800">{{ activeProgram ? activeProgram.title : 'Course Aktif' }}</h3>
                        <p class="text-sm text-gray-600">{{ activeProgram ? 'Lanjutkan pembelajaran' : 'Belum ada course aktif' }}</p>
                    </div>
                    <a :href="activeProgram ? '/course/' + activeProgram.id : '/course'"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition">
                        {{ activeProgram ? 'Lanjutkan' : 'Pilih Course' }}
                    </a>
                </div>

                <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-purple-800 flex items-center gap-2">🎓 Lihat Sertifikat</h3>
                        <p class="text-sm text-gray-600">{{ purchasedCourses.length > 0 ? 'Sertifikat kompetensi siap diunduh.' : 'Selesaikan course untuk mendapat sertifikat' }}</p>
                    </div>
                    <a :href="routes.certificate"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition">Lihat</a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid md:grid-cols-3 gap-6 mb-10">
                <a :href="routes.course"
                    class="bg-white shadow rounded-lg p-6 flex flex-col items-center hover:scale-105 transition group">
                    <span class="text-3xl mb-2 group-hover:animate-bounce">📚</span>
                    <span class="font-bold text-purple-700">Kursus</span>
                    <span class="text-xs text-gray-500 mt-1">Lihat semua kelas & promo terbaru</span>
                </a>
                <a :href="routes.tanyaMentor"
                    class="bg-white shadow rounded-lg p-6 flex flex-col items-center hover:scale-105 transition group">
                    <span class="text-3xl mb-2 group-hover:animate-bounce">💬</span>
                    <span class="font-bold text-purple-700">Tanya Mentor</span>
                    <span class="text-xs text-gray-500 mt-1">Diskusi & Tanya jawab</span>
                </a>
                <a :href="routes.reward"
                    class="bg-white shadow rounded-lg p-6 flex flex-col items-center hover:scale-105 transition group">
                    <span class="text-3xl mb-2 group-hover:animate-bounce">🎁</span>
                    <span class="font-bold text-purple-700">Ambil Reward</span>
                    <span class="text-xs text-gray-500 mt-1">Cek hadiah & voucher</span>
                </a>
            </div>

            <!-- Level Progress - hanya tampil jika ada course -->
            <div v-if="purchasedCourses.length > 0" class="bg-white shadow rounded-lg p-6 flex items-center gap-4 mb-10">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Level Kamu</div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-purple-700 text-lg">Level {{ userLevel }}</span>
                        <div class="w-40 bg-gray-200 h-3 rounded-full">
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-3 rounded-full" :style="{ width: levelProgress + '%' }">
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ levelProgress }}% ke Level {{ userLevel + 1 }}</span>
                    </div>
                </div>
                <span class="ml-auto bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-bold">XP:
                    {{ userXP }}</span>
            </div>

            <!-- Notifications -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-purple-800 mb-4 flex items-center gap-2">🔔 Notifikasi Terbaru</h2>
                <ul class="list-disc pl-6 text-sm text-gray-700 space-y-2">
                    <li v-for="notification in notifications" :key="notification.id">
                        <span :class="notification.badgeClass">{{ notification.type }}</span>
                        {{ notification.message }}
                    </li>
                </ul>
                <a href="#" class="block text-right text-purple-700 hover:underline mt-2 text-sm font-bold">Lihat Semua
                    Notifikasi</a>
            </div>

            <!-- Leaderboard - hanya tampil jika ada course -->
            <div v-if="purchasedCourses.length > 0" class="bg-white shadow rounded-lg p-6 mt-8">
                <h3 class="text-lg font-bold text-purple-800 mb-2 flex items-center gap-2">🏆 Leaderboard Mingguan</h3>
                <ol class="list-decimal pl-6 text-gray-700 text-sm space-y-1">
                    <li><span class="font-bold text-purple-700">Kamu</span> - {{ userXP }} XP <span
                            class="ml-2 bg-green-200 text-green-800 px-2 py-0.5 rounded-full text-xs">TOP 1</span></li>
                    <li>Bregas - 1100 XP</li>
                    <li>Yafa - 950 XP</li>
                </ol>
            </div>
        </div>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    name: 'UserDashboard',
    props: {
        user: {
            type: Object,
            default: () => ({ name: '' })
        },
        laravelRoutes: {
            type: Object,
            default: () => ({
                dashboard: '/dashboard',
                profileShow: '/user/profile',
                passwordChange: '/user/password/change',
                reward: '/user/reward',
                logout: '/logout',
                certificate: '/certificate',
                course: '/course',
                tanyaMentor: '/tanya-mentor',
            })
        },
        assetBaseUrl: {
            type: String,
            default: '/'
        }
    },

    data() {
        return {
            // UI State
            open: false,
            isScrolled: false,
            loading: true,
            loadAttempts: 0,
            maxLoadAttempts: 3,

            // Progress Data
            progress: 0,
            modulSelesai: 0,
            totalModul: 20,
            userLevel: 1,
            levelProgress: 0,
            userXP: 100,

            // Course Data
            purchasedCourses: [],
            activeProgram: null,

            // Notification Data
            showPurchaseNotification: false,
            latestPurchasedCourse: '',
            notifications: [
                {
                    id: 1,
                    type: 'Welcome',
                    badgeClass: 'inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-2',
                    message: '🎉 Selamat datang di Dunia Coding! Mulai perjalanan coding kamu sekarang.'
                }
            ]
        };
    },

    computed: {
        userName() {
            return this.getUserName();
        },
        avatarUrl() {
            return this.assetBaseUrl + 'image/hajisodikin.jpg';
        },
        routes() {
            return this.laravelRoutes;
        },
        nextSessionDate() {
            if (!this.activeProgram) return '';

            const today = new Date();
            const nextMonday = new Date(today);
            const daysUntilMonday = (8 - today.getDay()) % 7 || 7;
            nextMonday.setDate(today.getDate() + daysUntilMonday);

            return nextMonday.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }) + ' - 19:00 WIB';
        }
    },

    async mounted() {
        console.log('🎯 Dashboard mounted - starting initialization...');
        this.setupAxios();

        // Check for payment success from URL
        const urlParams = new URLSearchParams(window.location.search);
        const paymentSuccess = urlParams.get('payment_success');
        const courseTitle = urlParams.get('course_title');

        if (paymentSuccess === 'true' && courseTitle) {
            console.log('💰 Payment success detected from URL');
            this.showSuccessNotification(decodeURIComponent(courseTitle));

            // Delay untuk memberi waktu payment notification diproses
            setTimeout(async () => {
                await this.loadPurchasedCoursesWithRetry();
            }, 3000);
        } else {
            await this.loadPurchasedCoursesWithRetry();
        }

        this.updateUserStats();
        window.addEventListener('scroll', this.handleScroll);
    },

    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    },

    methods: {
        // ===== SETUP AXIOS =====
        setupAxios() {
            // Set base URL jika belum di-set
            if (!axios.defaults.baseURL) {
                axios.defaults.baseURL = 'http://localhost:8000';
            }

            // Set default headers
            axios.defaults.headers.common['Accept'] = 'application/json';
            axios.defaults.headers.common['Content-Type'] = 'application/json';

            // Set auth token jika ada
            const token = localStorage.getItem('authToken');
            if (token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                console.log('✅ Auth token set globally');
            }

            console.log('🔧 Axios configured with base URL:', axios.defaults.baseURL);
        },

        // ===== USER NAME =====
        getUserName() {
            try {
                const userData = localStorage.getItem('user');
                if (userData) {
                    const user = JSON.parse(userData);
                    return user.name || 'Pengguna';
                }
            } catch (e) {
                console.error('Error getting user name:', e);
            }
            return 'Pengguna';
        },

        // ===== LOAD COURSES WITH RETRY =====
        async loadPurchasedCoursesWithRetry() {
            console.log('🔄 Starting loadPurchasedCoursesWithRetry...');

            for (let attempt = 1; attempt <= this.maxLoadAttempts; attempt++) {
                console.log(`🎯 Load attempt ${attempt}/${this.maxLoadAttempts}`);

                try {
                    await this.loadPurchasedCourses();

                    // Jika berhasil dan ada course, keluar dari loop
                    if (this.purchasedCourses.length > 0) {
                        console.log('✅ Courses loaded successfully!');
                        return;
                    }

                    // Jika tidak ada course tapi payment success, tunggu sebelum retry
                    if (attempt < this.maxLoadAttempts) {
                        console.log(`⏳ No courses found, waiting before retry...`);
                        await this.delay(2000);
                    }

                } catch (error) {
                    console.error(`❌ Load attempt ${attempt} failed:`, error);

                    if (attempt < this.maxLoadAttempts) {
                        await this.delay(1000);
                    }
                }
            }

            console.log('⚠️ All load attempts exhausted');
        },

        // ===== LOAD PURCHASED COURSES =====
        async loadPurchasedCourses() {
            try {
                console.log('🔍 Starting loadPurchasedCourses...');

                const token = localStorage.getItem('authToken');

                if (!token) {
                    console.log('❌ No token found, skipping course load');
                    this.loading = false;
                    this.updateEmptyStateNotifications();
                    return;
                }

                console.log('✅ Token found, making API request...');

                // FIXED: Menggunakan URL yang konsisten
                const response = await axios.get('/api/my-courses', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                console.log('📡 API Response Status:', response.status);
                console.log('📄 API Response Data:', response.data);

                if (response.data.success) {
                    this.purchasedCourses = response.data.courses || [];
                    console.log('✅ Purchased courses loaded:', this.purchasedCourses.length);

                    if (this.purchasedCourses.length > 0) {
                        // Set active program (course terbaru)
                        this.activeProgram = this.purchasedCourses[0];
                        console.log('🎯 Active program set:', this.activeProgram);
                        this.updatePurchasedStateNotifications();

                        // Force reactivity update
                        this.$forceUpdate();
                    } else {
                        console.log('📝 No purchased courses found');
                        this.activeProgram = null;
                        this.updateEmptyStateNotifications();
                    }

                    this.updateUserStats();

                } else {
                    console.error('❌ API returned success: false', response.data);
                    this.purchasedCourses = [];
                    this.updateEmptyStateNotifications();
                }

            } catch (error) {
                console.error('💥 Error loading purchased courses:', error);
                console.error('📄 Error response:', error.response?.data);
                console.error('🔢 Error status:', error.response?.status);

                if (error.response?.status === 401) {
                    console.log('🔑 Token expired or invalid, clearing auth data');
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('user');
                    delete axios.defaults.headers.common['Authorization'];
                    this.purchasedCourses = [];
                    this.activeProgram = null;
                    this.updateEmptyStateNotifications();
                } else {
                    // Jika bukan 401, lempar error untuk retry
                    throw error;
                }

            } finally {
                this.loading = false;
                console.log('✅ loadPurchasedCourses completed');
            }
        },

        // ===== SUCCESS NOTIFICATION =====
        showSuccessNotification(courseTitle) {
            console.log('🎉 Showing success notification for:', courseTitle);

            this.showPurchaseNotification = true;
            this.latestPurchasedCourse = courseTitle;

            // Tambah notifikasi ke daftar
            this.notifications.unshift({
                id: Date.now(),
                type: 'Baru',
                badgeClass: 'inline-block bg-green-100 text-green-700 px-2 py-0.5 rounded mr-2',
                message: `🎉 Selamat! Kamu berhasil membeli "${courseTitle}". Selamat belajar!`
            });

            // Update stats
            this.updateUserStats();
        },

        // ===== DISMISS NOTIFICATION =====
        dismissNotification() {
            this.showPurchaseNotification = false;

            // Hapus parameter dari URL
            const url = new URL(window.location);
            url.searchParams.delete('payment_success');
            url.searchParams.delete('course_title');
            window.history.replaceState({}, document.title, url.pathname);
        },

        // ===== UPDATE NOTIFICATIONS =====
        updateEmptyStateNotifications() {
            this.notifications = [
                {
                    id: 1,
                    type: 'Welcome',
                    badgeClass: 'inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-2',
                    message: '🎉 Selamat datang di Dunia Coding! Mulai perjalanan coding kamu sekarang.'
                },
                {
                    id: 2,
                    type: 'Info',
                    badgeClass: 'inline-block bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded mr-2',
                    message: '📚 Ada course baru tersedia! Cek sekarang dan dapatkan early bird discount.'
                },
                {
                    id: 3,
                    type: 'Promo',
                    badgeClass: 'inline-block bg-green-100 text-green-700 px-2 py-0.5 rounded mr-2',
                    message: '🤑 Promo spesial: diskon 20% untuk course pertama kamu!'
                }
            ];
        },

        updatePurchasedStateNotifications() {
            this.notifications = [
                {
                    id: 1,
                    type: 'Baru',
                    badgeClass: 'inline-block bg-green-100 text-green-700 px-2 py-0.5 rounded mr-2',
                    message: '🎉 Congrats! Kamu sudah memulai perjalanan coding. #NoMoreSkip'
                },
                {
                    id: 2,
                    type: 'Info',
                    badgeClass: 'inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-2',
                    message: '📅 Jangan lupa ikuti sesi mentoring untuk progress yang lebih optimal!'
                },
                {
                    id: 3,
                    type: 'Tips',
                    badgeClass: 'inline-block bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded mr-2',
                    message: '💡 Konsisten belajar 30 menit setiap hari lebih efektif daripada belajar 3 jam seminggu sekali.'
                }
            ];
        },

        // ===== UPDATE USER STATS =====
        updateUserStats() {
            // Update stats berdasarkan jumlah course yang dibeli
            const baseXP = 100;
            const courseBonus = this.purchasedCourses.length * 200;
            const progressBonus = this.modulSelesai * 50;

            this.userXP = baseXP + courseBonus + progressBonus;

            // Update level berdasarkan XP
            this.userLevel = Math.floor(this.userXP / 500) + 1;
            const currentLevelXP = (this.userLevel - 1) * 500;
            const nextLevelXP = this.userLevel * 500;
            this.levelProgress = Math.floor(((this.userXP - currentLevelXP) / (nextLevelXP - currentLevelXP)) * 100);

            // Update progress berdasarkan course yang dibeli
            if (this.purchasedCourses.length > 0) {
                this.modulSelesai = Math.max(this.modulSelesai, 2);
                this.progress = Math.round((this.modulSelesai / this.totalModul) * 100);
            } else {
                this.modulSelesai = 0;
                this.progress = 0;
            }
        },

        // ===== HELPER METHODS =====
        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        getCourseImageUrl(imagePath) {
            if (!imagePath) return '/images/default-course.jpg';

            if (imagePath.startsWith('/storage/') || imagePath.startsWith('http')) {
                return imagePath;
            }
            return `/storage/${imagePath}`;
        },

        formatDate(dateString) {
            if (!dateString) return '';

            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        },

        increaseProgress() {
            if (this.progress < 100 && this.purchasedCourses.length > 0) {
                this.modulSelesai++;
                this.progress = Math.round((this.modulSelesai / this.totalModul) * 100);
                if (this.progress > 100) this.progress = 100;

                // Update XP saat progress bertambah
                this.userXP += 50;
                this.updateUserStats();

                // Tambah notifikasi progress
                this.notifications.unshift({
                    id: Date.now(),
                    type: 'Progress',
                    badgeClass: 'inline-block bg-green-100 text-green-700 px-2 py-0.5 rounded mr-2',
                    message: `🎯 Selamat! Kamu berhasil menyelesaikan modul ke-${this.modulSelesai}. Keep going!`
                });
            }
        },

        handleScroll() {
            this.isScrolled = window.pageYOffset > 100;
            if (this.isScrolled) {
                this.open = false;
            }
        },

        // ===== MANUAL REFRESH =====
        async manualRefreshCourses() {
            console.log('🔄 Manual refresh triggered');
            this.loading = true;
            this.loadAttempts = 0;
            await this.loadPurchasedCoursesWithRetry();
        },

        // ===== DEBUG METHODS =====
        async debugCourseData() {
            console.log('🔧 Manual Debug Started...');

            try {
                const token = localStorage.getItem('authToken');

                if (!token) {
                    console.log('❌ No token found');
                    return;
                }

                // Check user info
                console.log('1️⃣ Checking user info...');
                const userResponse = await axios.get('/api/user', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                console.log('👤 User data:', userResponse.data);

                // Check raw payments
                console.log('2️⃣ Checking raw payments...');
                const paymentsResponse = await axios.get('/api/debug/raw-payments', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                console.log('💳 Payments data:', paymentsResponse.data);

                // Check my courses
                console.log('3️⃣ Checking my courses...');
                const coursesResponse = await axios.get('/api/my-courses', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                console.log('📚 Courses result:', coursesResponse.data);

                return {
                    user: userResponse.data,
                    payments: paymentsResponse.data,
                    courses: coursesResponse.data
                };

            } catch (error) {
                console.error('💥 Debug error:', error);
                return { error: error.message };
            }
        }
    }
};
</script>

<style scoped>
.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-12px);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translateY(0);
    }
    40%, 43% {
        transform: translateY(-30px);
    }
    70% {
        transform: translateY(-15px);
    }
    90% {
        transform: translateY(-4px);
    }
}

.transition {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

.opacity-0 {
    opacity: 0;
}

.opacity-100 {
    opacity: 1;
}

.pointer-events-none {
    pointer-events: none;
}

/* Loading animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
