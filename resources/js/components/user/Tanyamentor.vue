<template>
    <section class="bg-gradient-to-br from-purple-50 to-indigo-50 min-h-screen py-16">
        <div class="max-w-3xl mx-auto px-6 pt-24">
            <div class="bg-white shadow rounded-xl p-8 mb-12">
                <h2 class="text-3xl font-extrabold text-purple-800 mb-4 flex items-center gap-2">
                    💬 Tanya Mentor
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full ml-2">Diskusi & Tanya Jawab</span>
                </h2>
                <p class="text-gray-600 mb-6">Punya pertanyaan soal materi, tugas, atau karir IT? Yuk, tanya mentor dan
                    diskusi bareng teman-teman Gen Z lainnya. Jawaban cepat, no judgement!</p>
                <div class="mb-6 text-center">
                    <button @click="showComingSoonAlert"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-6 py-2 rounded-lg shadow hover:scale-105 transition">Tanya
                        Sekarang 🚀</button>
                </div>
                <div>
                    <h3 class="font-bold text-purple-700 mb-3">Pertanyaan Terbaru</h3>
                    <ul class="space-y-4">
                        <li v-for="question in latestQuestions" :key="question.id"
                            class="p-4 bg-purple-50 rounded-lg flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <span class="font-semibold text-gray-800">{{ question.text }}</span>
                                <div class="text-xs text-gray-500 mt-1">oleh
                                    <span class="font-bold text-purple-700">{{ question.author }}</span> •
                                    {{ question.timeAgo }}
                                </div>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full mt-2 md:mt-0"
                                :class="{
                                    'bg-green-100 text-green-700': question.status === 'Dijawab',
                                    'bg-yellow-100 text-yellow-700': question.status === 'Pending'
                                }">{{ question.status }}</span>
                        </li>
                    </ul>
                </div>
                <div class="text-center mt-8">
                    <p class="text-sm text-gray-600 mb-2">Diskusi seru, mentor ramah, dan komunitas Gen Z siap bantu kamu! ✨
                    </p>
                    <a href="#"
                        class="inline-block bg-blue-500 text-white font-bold px-6 py-2 rounded-full shadow hover:scale-105 transition">Lihat
                        Semua Diskusi</a>
                </div>
            </div>
        </div>
        <div class="text-center mt-8 animate-bounce">
            <a :href="dashboardRoute"
                class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-purple-800 text-white font-medium rounded-full hover:from-purple-700 hover:to-purple-900 transition-all shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:-translate-x-1 transition-transform"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </section>
</template>

<script>

import Dashboard from './Dashboard.vue';

export default {
    name: 'TanyaMentorPage',
    props: {
        // This prop will be passed from your Laravel Blade view
        // to provide the URL for the dashboard.
        dashboardRoute: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            latestQuestions: [
                {
                    id: 1,
                    text: 'Bagaimana cara deploy website ke Vercel?',
                    author: 'Rizky',
                    timeAgo: '2 jam lalu',
                    status: 'Dijawab'
                },
                {
                    id: 2,
                    text: 'Tips biar nggak bosen belajar coding?',
                    author: 'Nabila',
                    timeAgo: '5 jam lalu',
                    status: 'Pending'
                },
                {
                    id: 3,
                    text: 'Apa bedanya UI & UX?',
                    author: 'Bregas',
                    timeAgo: '1 hari lalu',
                    status: 'Dijawab'
                }
            ]
        };
    },
    methods: {
        showComingSoonAlert() {
            alert('Fitur form tanya coming soon!');
            // In a real application, you'd likely transition to a form component
            // or show a modal here instead of a simple alert.
        }
    }
};
</script>

<style scoped>
/* Tailwind CSS classes should already be globally available from your main CSS.
   Any custom animations or specific styles not covered by Tailwind can go here. */
.animate-bounce {
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(-5%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

.transition {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>