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
                        Siap upgrade skill hari ini? Yuk cek progres dan tantangan barumu!
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-700 flex items-center gap-2">🔥 Program Aktif</h3>
                    <a href='/course_content' class="text-2xl font-bold text-purple-800 mt-2">Belajar Laravel & ReactJS</a>
                    <p class="text-sm text-gray-500 mt-1">Dimulai: 1 Mei 2025</p>
                    <span class="inline-block mt-2 bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">On
                        Fire!</span>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-700 flex items-center gap-2">📈 Progres Belajar</h3>
                    <div class="w-full bg-gray-200 h-4 rounded-full mt-2 relative overflow-hidden">
                        <div id="progressBar"
                            class="bg-gradient-to-r from-purple-500 to-indigo-500 h-4 rounded-full transition-all duration-700"
                            :style="{ width: progress + '%' }"></div>
                        <span id="progressPercent"
                            class="absolute right-3 top-0 text-xs font-bold text-purple-700 h-4 flex items-center">{{ progress }}%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1"><span id="progressText">{{ modulSelesai }} dari
                            {{ totalModul }} modul selesai</span> <span class="ml-1">🎯</span></p>
                    <button @click="increaseProgress"
                        class="mt-3 px-4 py-2 bg-purple-600 text-white rounded-lg font-bold hover:bg-purple-700 transition">Tambah
                        Progress 🚀</button>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-700 flex items-center gap-2">⏰ Sesi Selanjutnya</h3>
                    <p class="text-base mt-2 font-bold">Senin, 19 Mei 2025 - 19:00 WIB</p>
                    <p class="text-sm text-gray-500">Bareng: <span class="font-semibold text-indigo-700">Unzurna Raja
                            Mid</span></p>
                    <span class="inline-block mt-2 bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Jangan
                        Telat!</span>
                </div>
            </div>

            <div class="bg-gradient-to-r from-indigo-100 to-purple-100 rounded-lg p-4 text-center mb-10">
                <span class="text-purple-800 font-bold">👨‍💻 12.000+</span> Gen Z sudah belajar bareng Dunia Coding!
                <span class="ml-2 text-green-600 font-semibold">#NoMoreSkip</span>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Bab 4: Dasar JavaScript</p>
                    </div>
                    <a href="#"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition">Mulai</a>
                </div>

                <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-purple-800 flex items-center gap-2">🎓 Lihat Sertifikat</h3>
                        <p class="text-sm text-gray-600">Sertifikat kompetensi siap diunduh.</p>
                    </div>
                    <a :href="routes.certificate"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition">Lihat</a>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-6 mb-10">
                <a :href="routes.courses"
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

            <div class="bg-white shadow rounded-lg p-6 flex items-center gap-4 mb-10">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Level Kamu</div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-purple-700 text-lg">Level 3</span>
                        <div class="w-40 bg-gray-200 h-3 rounded-full">
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-3 rounded-full" style="width: 60%">
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">60% ke Level 4</span>
                    </div>
                </div>
                <span class="ml-auto bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-bold">XP:
                    1200</span>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-purple-800 mb-4 flex items-center gap-2">🔔 Notifikasi Terbaru</h2>
                <ul class="list-disc pl-6 text-sm text-gray-700 space-y-2">
                    <li>
                        <span class="inline-block bg-green-100 text-green-700 px-2 py-0.5 rounded mr-2">Baru</span>
                        🎉 Congrats! Kamu sudah selesaikan 3 modul awal. #NoMoreSkip
                    </li>
                    <li>
                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-2">Info</span>
                        📅 Jadwal sesi mentoring update, siap-siap join bareng bestie!
                    </li>
                    <li>
                        <span class="inline-block bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded mr-2">Promo</span>
                        🤑 Promo alumni: diskon 20% buat next level, buruan cek!
                    </li>
                </ul>
                <a href="#" class="block text-right text-purple-700 hover:underline mt-2 text-sm font-bold">Lihat Semua
                    Notifikasi</a>
            </div>

            <div class="bg-white shadow rounded-lg p-6 mt-8">
                <h3 class="text-lg font-bold text-purple-800 mb-2 flex items-center gap-2">🏆 Leaderboard Mingguan</h3>
                <ol class="list-decimal pl-6 text-gray-700 text-sm space-y-1">
                    <li><span class="font-bold text-purple-700">Kamu</span> - 1200 XP <span
                            class="ml-2 bg-green-200 text-green-800 px-2 py-0.5 rounded-full text-xs">TOP 1</span></li>
                    <li>Bregas - 1100 XP</li>
                    <li>Yafa - 950 XP</li>
                </ol>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'UserDashboard',
    props: {
        // You'll pass user data and route names from your Laravel controller
        // For example:
        user: {
            type: Object,
            default: () => ({ name: 'User' })
        },
        // Routes would ideally be passed as an object to avoid hardcoding in Vue
        laravelRoutes: {
            type: Object,
            default: () => ({
                dashboard: '/dashboard',
                profileShow: '/user/profile',
                passwordChange: '/user/password/change',
                reward: '/user/reward',
                logout: '/logout',
                certificate: '/certificate',
                courses: '/courses',
                tanyaMentor: 'Tanya_Mentor',
            })
        },
        // Base URL for assets, like 'image/hajisodikin.jpg'
        assetBaseUrl: {
            type: String,
            default: '/'
        }
    },
    data() {
        return {
            open: false, // For the dropdown menu
            isScrolled: false, // For checking scroll position
            progress: 45, // Progress percentage
            modulSelesai: 9,
            totalModul: 20,
        };
    },
    computed: {
        userName() {
            // Use the passed user prop, fallback to 'User'
            return this.user.name || 'User';
        },
        avatarUrl() {
            // Construct the full URL for the avatar image
            return this.assetBaseUrl + 'image/hajisodikin.jpg';
        },
        routes() {
            return this.laravelRoutes;
        }
    },
    methods: {
        increaseProgress() {
            if (this.progress < 100) {
                this.modulSelesai++;
                this.progress = Math.round((this.modulSelesai / this.totalModul) * 100);
                if (this.progress > 100) this.progress = 100;
            }
        },
        handleScroll() {
            this.isScrolled = window.pageYOffset > 100;
            if (this.isScrolled) {
                this.open = false; // Close dropdown on scroll if it's open
            }
        }
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    }
};
</script>

<style scoped>
/* Scoped styles ensure these only apply to this component */
.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-12px);
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
</style>
