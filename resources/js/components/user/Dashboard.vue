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
                        :class="{ 'ring-2 ring-purple-400 bg-purple-50': activeProgram && activeProgram.id === course.id }"
                    >
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-purple-800 mb-2">{{ course.title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Dibeli: {{ formatDate(course.purchased_at) }}
                                </p>

                                <!-- Progress untuk course ini -->
                                <div v-if="courseProgresses[course.id]" class="mb-3">
                                    <div class="w-full bg-gray-200 h-2 rounded-full">
                                        <div
                                            class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300"
                                            :style="{ width: courseProgresses[course.id].percentage + '%' }"
                                        ></div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">
                                        {{ courseProgresses[course.id].completed }} dari {{ courseProgresses[course.id].total }} materi selesai
                                        ({{ Math.round(courseProgresses[course.id].percentage) }}%)
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                                        ✓ Sudah Dibeli
                                    </span>
                                    <span v-if="activeProgram && activeProgram.id === course.id"
                                          class="inline-block bg-orange-100 text-orange-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                                        🔥 Aktif
                                    </span>
                                </div>

                                <button
                                    @click="startLearning(course)"
                                    class="inline-block bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition text-sm"
                                >
                                    {{ activeProgram && activeProgram.id === course.id ? 'Lanjutkan Belajar' : 'Mulai Belajar' }} 🚀
                                </button>
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
                        <button @click="startLearning(activeProgram)" class="block w-full text-left">
                            <h4 class="text-xl font-bold text-purple-800 mt-2 hover:text-purple-600 transition">
                                {{ activeProgram.title }}
                            </h4>
                        </button>
                        <p class="text-sm text-gray-500 mt-1">
                            Terakhir dipelajari: {{ formatDate(activeProgram.last_accessed || activeProgram.purchased_at) }}
                        </p>

                        <!-- Progress untuk program aktif -->
                        <div v-if="currentProgress.total > 0" class="mt-3">
                            <div class="w-full bg-gray-200 h-3 rounded-full">
                                <div
                                    class="bg-gradient-to-r from-purple-500 to-indigo-500 h-3 rounded-full transition-all duration-500"
                                    :style="{ width: currentProgress.percentage + '%' }"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                {{ currentProgress.completed }}/{{ currentProgress.total }} materi ({{ Math.round(currentProgress.percentage) }}%)
                            </p>
                        </div>

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
                        <div
                            class="bg-gradient-to-r from-purple-500 to-indigo-500 h-4 rounded-full transition-all duration-700"
                            :style="{ width: currentProgress.percentage + '%' }"
                        ></div>
                        <span
                            class="absolute right-3 top-0 text-xs font-bold text-purple-700 h-4 flex items-center"
                        >{{ Math.round(currentProgress.percentage) }}%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                        <span>{{ currentProgress.completed }} dari {{ currentProgress.total }} materi selesai</span>
                        <span class="ml-1">🎯</span>
                    </p>
                    <button
                        v-if="activeProgram"
                        @click="startLearning(activeProgram)"
                        class="mt-3 px-4 py-2 bg-purple-600 text-white rounded-lg font-bold hover:bg-purple-700 transition"
                    >
                        Lanjutkan Belajar 🚀
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
                        <div v-if="activeProgram && currentProgress.total > 0" class="mt-2">
                            <p class="text-xs text-gray-500">Progress: {{ Math.round(currentProgress.percentage) }}%</p>
                        </div>
                    </div>
                    <button
                        v-if="activeProgram"
                        @click="startLearning(activeProgram)"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition"
                    >
                        Lanjutkan
                    </button>
                    <a
                        v-else
                        href="/course"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition"
                    >
                        Pilih Course
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
                tanyaMentor: '/tanya_mentor',
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

            // Course Data
            purchasedCourses: [],
            activeProgram: null,
            courseProgresses: {},

            // Progress Data
            userLevel: 1,
            levelProgress: 0,
            userXP: 100,

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
        },
        currentProgress() {
            if (!this.activeProgram || !this.courseProgresses[this.activeProgram.id]) {
                return { completed: 0, total: 0, percentage: 0 };
            }
            return this.courseProgresses[this.activeProgram.id];
        }
    },

    async mounted() {
        console.log('🎯 Dashboard mounted - starting initialization...');
        this.setupAxios();

        const urlParams = new URLSearchParams(window.location.search);
        const paymentSuccess = urlParams.get('payment_success');
        const courseTitle = urlParams.get('course_title');

        if (paymentSuccess === 'true' && courseTitle) {
            console.log('💰 Payment success detected from URL');
            this.showSuccessNotification(decodeURIComponent(courseTitle));

            setTimeout(async () => {
                await this.loadPurchasedCoursesWithRetry();
            }, 3000);
        } else {
            await this.loadPurchasedCoursesWithRetry();
        }

        this.loadActiveProgram();
        this.updateUserStats();
        window.addEventListener('scroll', this.handleScroll);
    },

    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    },

    methods: {
        // ===== SETUP AXIOS =====
        setupAxios() {
            if (!axios.defaults.baseURL) {
                axios.defaults.baseURL = 'http://localhost:8000';
            }

            axios.defaults.headers.common['Accept'] = 'application/json';
            axios.defaults.headers.common['Content-Type'] = 'application/json';

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

                    if (this.purchasedCourses.length > 0) {
                        console.log('✅ Courses loaded successfully!');
                        await this.loadAllCourseProgresses();
                        return;
                    }

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
                        this.updatePurchasedStateNotifications();
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
                    throw error;
                }

            } finally {
                this.loading = false;
                console.log('✅ loadPurchasedCourses completed');
            }
        },

        // ===== LOAD ALL COURSE PROGRESSES =====
        async loadAllCourseProgresses() {
            console.log('📊 Loading progress for all courses...');

            for (const course of this.purchasedCourses) {
                const progress = await this.loadCourseProgress(course.id);
                this.$set(this.courseProgresses, course.id, progress);
            }

            console.log('✅ All course progresses loaded:', this.courseProgresses);
        },

        // ===== LOAD COURSE PROGRESS =====
        async loadCourseProgress(courseId) {
            try {
                console.log(`📊 Loading progress for course ${courseId}...`);

                const response = await axios.get(`/api/course-content/course/${courseId}`);

                if (response.data.success) {
                    const courseData = response.data.data;
                    const materis = courseData.materis || [];

                    if (materis.length === 0) {
                        console.log(`⚠️ No materials found for course ${courseId}`);
                        return { completed: 0, total: 0, percentage: 0 };
                    }

                    const progressKey = `course_progress_${courseId}`;
                    let completedMateris = [];

                    try {
                        const savedProgress = localStorage.getItem(progressKey);
                        if (savedProgress) {
                            const progressData = JSON.parse(savedProgress);
                            completedMateris = progressData.completedMateris || [];
                        }
                    } catch (e) {
                        console.error('Error loading progress from localStorage:', e);
                    }

                    const completed = completedMateris.length;
                    const total = materis.length;
                    const percentage = total > 0 ? (completed / total) * 100 : 0;

                    console.log(`✅ Progress loaded for course ${courseId}: ${completed}/${total} (${Math.round(percentage)}%)`);

                    return { completed, total, percentage };
                } else {
                    console.error(`❌ Failed to load course content for ${courseId}:`, response.data);
                    return { completed: 0, total: 0, percentage: 0 };
                }

            } catch (error) {
                console.error(`💥 Error loading progress for course ${courseId}:`, error);
                return { completed: 0, total: 0, percentage: 0 };
            }
        },

        // ===== START LEARNING (MAIN FUNCTION) =====
        async startLearning(course) {
            console.log('🚀 Starting learning for course:', course.title);

            // Update active program
            this.activeProgram = course;

            // Update last accessed time
            this.activeProgram.last_accessed = new Date().toISOString();

            // Save active program to localStorage
            this.saveActiveProgram();

            // Add notification
            this.notifications.unshift({
                id: Date.now(),
                type: 'Learning',
                badgeClass: 'inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-2',
                message: `🚀 Mulai belajar "${course.title}". Semangat belajarnya!`
            });

            // Update stats
            this.updateUserStats();

            // Navigate to course content
            window.location.href = `/course/${course.id}`;
        },

        // ===== SAVE/LOAD ACTIVE PROGRAM =====
        saveActiveProgram() {
            if (this.activeProgram) {
                try {
                    localStorage.setItem('active_program', JSON.stringify(this.activeProgram));
                    console.log('💾 Active program saved to localStorage');
                } catch (error) {
                    console.error('Error saving active program:', error);
                }
            }
        },

        loadActiveProgram() {
            try {
                const savedProgram = localStorage.getItem('active_program');
                if (savedProgram) {
                    const programData = JSON.parse(savedProgram);

                    // Verify that the saved program exists in purchased courses
                    const existingCourse = this.purchasedCourses.find(course => course.id === programData.id);
                    if (existingCourse) {
                        this.activeProgram = existingCourse;
                        console.log('✅ Active program loaded from localStorage:', this.activeProgram.title);
                    } else {
                        console.log('⚠️ Saved active program not found in purchased courses');
                        localStorage.removeItem('active_program');
                    }
                }

                // If no active program and we have courses, set the most recently purchased as active
                if (!this.activeProgram && this.purchasedCourses.length > 0) {
                    this.activeProgram = this.purchasedCourses[0];
                    this.saveActiveProgram();
                    console.log('🎯 Set most recent course as active program:', this.activeProgram.title);
                }

            } catch (error) {
                console.error('Error loading active program:', error);
                localStorage.removeItem('active_program');
            }
        },

        // ===== SUCCESS NOTIFICATION =====
        showSuccessNotification(courseTitle) {
            console.log('🎉 Showing success notification for:', courseTitle);

            this.showPurchaseNotification = true;
            this.latestPurchasedCourse = courseTitle;

            this.notifications.unshift({
                id: Date.now(),
                type: 'Baru',
                badgeClass: 'inline-block bg-green-100 text-green-700 px-2 py-0.5 rounded mr-2',
                message: `🎉 Selamat! Kamu berhasil membeli "${courseTitle}". Selamat belajar!`
            });

            this.updateUserStats();
        },

        // ===== DISMISS NOTIFICATION =====
        dismissNotification() {
            this.showPurchaseNotification = false;

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
            // Calculate total progress from all courses
            let totalCompleted = 0;
            let totalMaterials = 0;

            Object.values(this.courseProgresses).forEach(progress => {
                totalCompleted += progress.completed;
                totalMaterials += progress.total;
            });

            // Update XP based on courses and progress
            const baseXP = 100;
            const courseBonus = this.purchasedCourses.length * 200;
            const progressBonus = totalCompleted * 50;

            this.userXP = baseXP + courseBonus + progressBonus;

            // Update level based on XP
            this.userLevel = Math.floor(this.userXP / 500) + 1;
            const currentLevelXP = (this.userLevel - 1) * 500;
            const nextLevelXP = this.userLevel * 500;
            this.levelProgress = Math.floor(((this.userXP - currentLevelXP) / (nextLevelXP - currentLevelXP)) * 100);

            console.log('📊 Stats updated:', {
                courses: this.purchasedCourses.length,
                totalCompleted,
                totalMaterials,
                userXP: this.userXP,
                userLevel: this.userLevel,
                levelProgress: this.levelProgress
            });
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
    },

    // ===== WATCHERS =====
    watch: {
        // Watch for changes in purchased courses to update active program
        purchasedCourses: {
            handler(newCourses) {
                if (newCourses.length > 0 && !this.activeProgram) {
                    // Set the first course as active if no active program exists
                    this.activeProgram = newCourses[0];
                    this.saveActiveProgram();
                }

                // Load progress for all courses when courses change
                this.loadAllCourseProgresses();
            },
            deep: true
        },

        // Watch for changes in active program
        activeProgram: {
            handler(newProgram) {
                if (newProgram) {
                    this.saveActiveProgram();
                }
            },
            deep: true
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

/* Course card active state */
.ring-2 {
    box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.4);
}

/* Progress bar animations */
.transition-all {
    transition: all 0.5s ease-in-out;
}
</style>
