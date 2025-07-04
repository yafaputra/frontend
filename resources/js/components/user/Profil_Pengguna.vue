<template>
  <section class="bg-gray-50 min-h-screen text-gray-800 font-sans">
    <div class="max-w-4xl mx-auto px-8 pt-18 pb-16"> 

      <div class="mb-14">
        <h1 class="text-4xl lg:text-5xl font-bold text-purple-800 leading-tight">Profil Pengguna</h1>
        <p class="text-lg text-gray-600 mt-4">Perbarui informasi akunmu di bawah ini.</p>
      </div>

      <form @submit.prevent="submitProfile" enctype="multipart/form-data" class="bg-white shadow-xl rounded-2xl p-10 space-y-8">
        <div class="flex items-center gap-8 mb-10">
          <img id="avatarPreview"
               :src="currentAvatarUrl"
               alt="Avatar"
               class="w-28 h-28 md:w-32 md:h-32 rounded-full border-5 border-purple-600 shadow-lg object-cover">
          <div>
            <div class="font-bold text-purple-800 text-2xl md:text-3xl flex items-center gap-4">
              {{ formData.fullname || 'User' }}
              <span class="bg-yellow-200 text-yellow-800 text-base px-4 py-1.5 rounded-full badge-animate">Gen Z Squad 🦄</span>
            </div>
            <div class="text-base text-gray-500 mt-2">Aktif belajar sejak {{ userCreatedAtYear }}</div>
            <label class="text-purple-600 text-base hover:underline cursor-pointer mt-3 block">
              Ganti Foto Profil
              <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" @change="handleAvatarChange">
            </label>
          </div>
        </div>

        <div>
          <label class="block text-lg font-semibold text-gray-700 mb-3" for="fullname">Nama Lengkap</label>
          <input type="text" id="fullname" name="fullname"
                 v-model="formData.fullname"
                 class="form-input-custom" required>
                 </div>

        <div>
          <label class="block text-lg font-semibold text-gray-700 mb-3" for="username">Username</label>
          <input type="text" id="username" name="username"
                 v-model="formData.username"
                 class="form-input-custom">
                 </div>

        <div>
          <label class="block text-lg font-semibold text-gray-700 mb-3" for="dob">Tanggal Lahir</label>
          <input type="date" id="dob" name="dob"
                 v-model="formData.dob"
                 class="form-input-custom">
                 </div>

        <div>
          <label class="block text-lg font-semibold text-gray-700 mb-3" for="email">Email</label>
          <input type="email" id="email" name="email"
                 v-model="formData.email"
                 class="form-input-custom" required>
                 </div>

        <div>
          <label class="block text-lg font-semibold text-gray-700 mb-3" for="bio">Bio</label>
          <textarea id="bio" name="bio" rows="4"
                    v-model="formData.bio"
                    class="form-input-custom"
                    placeholder="Ceritain sedikit tentang dirimu..."></textarea>
                    </div>

        <div>
          <label class="block text-lg font-semibold text-gray-700 mb-3">Hobi / Interest</label>
          <div class="flex flex-wrap gap-5 mt-3">
            <label v-for="(label, value) in availableHobbies" :key="value" class="flex items-center gap-2.5 text-lg">
              <input type="checkbox" name="hobbies[]" :value="value" v-model="formData.hobbies" class="form-checkbox h-5 w-5 text-purple-600 rounded">
              <span>{{ label }}</span>
            </label>
          </div>
        </div>

        <div class="mb-8">
          <label class="block text-lg font-semibold text-gray-700 mb-3">Badge Koleksi</label>
          <div class="flex flex-wrap gap-4 mt-2">
            <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-base flex items-center gap-1 badge-animate">🔥 Aktif</span>
            <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-base flex items-center gap-1">💡 Fast Learner</span>
            <span class="bg-yellow-100 text-yellow-700 px-4 py-1.5 rounded-full text-base flex items-center gap-1">🏆 Top 3</span>
          </div>
        </div>

        <div class="mb-8">
          <label class="block text-lg font-semibold text-gray-700 mb-3">Level Kamu</label>
          <div class="flex items-center gap-4">
            <span class="font-bold text-purple-700 text-xl">Level {{ formData.level }}</span>
            <div class="w-full max-w-md bg-gray-200 h-4 rounded-full overflow-hidden">
              <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-4 rounded-full progress-animate"
                   :style="{ width: `${formData.progress}%` }"></div>
            </div>
            <span class="text-base text-gray-500">{{ formData.progress }}% ke Level {{ formData.level + 1 }}</span>
          </div>
        </div>

        <p class="text-lg text-purple-700 mb-8 italic">"{{ randomMotivasi }}"</p>

        <div class="flex justify-between items-center pt-6">
          <a href="/dashboard" class="text-purple-700 hover:underline text-lg font-medium">← Kembali ke Dashboard</a>
          <button type="submit"
                  :disabled="isSaving"
                  class="bg-purple-800 text-white font-bold px-8 py-3.5 rounded-lg hover:bg-purple-900 transition flex items-center gap-3 text-lg">
            <span v-if="!isSaving">Simpan Perubahan</span>
            <span v-else class="flex items-center">
              <svg class="animate-spin h-6 w-6 text-white mr-3" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              Menyimpan...
            </span>
          </button>
        </div>
        
        <div v-if="successMessage" class="mt-6 p-5 bg-green-100 text-green-700 rounded-lg text-lg">
          ✅ {{ successMessage }}
        </div>
        
        <div v-if="errorMessage" class="mt-6 p-5 bg-red-100 text-red-700 rounded-lg text-lg">
          ❌ {{ errorMessage }}
        </div>
      </form>

    </div>
  </section>
</template>

<script>
// (Bagian script tidak ada perubahan)
export default {
  props: {
    initialProfile: {
      type: Object,
      default: () => ({})
    },
    initialUser: {
      type: Object,
      default: () => ({})
    },
    updateRoute: {
      type: String,
      required: true,
      default: '/profile/update'
    },
    defaultAvatarPath: {
      type: String,
      default: '/image/hajisodikin.jpg'
    }
  },
  data() {
    return {
      formData: {
        fullname: this.initialProfile.fullname || this.initialUser.name || '',
        username: this.initialProfile.username || '',
        dob: this.initialProfile.dob || '',
        email: this.initialProfile.email || this.initialUser.email || '',
        bio: this.initialProfile.bio || 'Coding enthusiast & Error lover 💀',
        hobbies: this.initialProfile.hobbies ? JSON.parse(this.initialProfile.hobbies) : ['Ngoding', 'Gaming', 'Ngonten'],
        avatar: null,
        level: this.initialProfile.level ?? 3,
        progress: this.initialProfile.progress ?? 60,
      },
      currentAvatarPreviewUrl: this.initialProfile.avatar ? `/storage/${this.initialProfile.avatar}` : this.defaultAvatarPath,
      isSaving: false,
      successMessage: '',
      errorMessage: '',
      availableHobbies: {
        'Ngoding': '💻 Ngoding',
        'Gaming': '🎮 Gaming',
        'Desain': '🎨 Desain',
        'Nge-vlog': '📹 Nge-vlog',
        'Ngonten': '📱 Ngonten'
      },
      motivasi: [
        "Setiap error adalah langkah menuju jago! 💪",
        "Belajar hari ini, sukses esok hari 🚀",
        "Jangan takut gagal, Gen Z selalu bangkit! 🔥",
        "Skillmu = aset masa depanmu ✨"
      ]
    };
  },
  computed: {
    userCreatedAtYear() {
      if (this.initialUser.created_at) {
        return new Date(this.initialUser.created_at).getFullYear();
      }
      return new Date().getFullYear();
    },
    randomMotivasi() {
      const randomIndex = Math.floor(Math.random() * this.motivasi.length);
      return this.motivasi[randomIndex];
    },
    currentAvatarUrl() {
        return this.currentAvatarPreviewUrl;
    }
  },
  methods: {
    handleAvatarChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.formData.avatar = file;
        const reader = new FileReader();
        reader.onload = (e) => {
          this.currentAvatarPreviewUrl = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        this.formData.avatar = null;
        this.currentAvatarPreviewUrl = this.initialProfile.avatar ? `/storage/${this.initialProfile.avatar}` : this.defaultAvatarPath;
      }
    },
    async submitProfile() {
      this.isSaving = true;
      this.successMessage = '';
      this.errorMessage = '';

      const dataToSubmit = new FormData();
      for (const key in this.formData) {
        if (key === 'hobbies') {
          this.formData.hobbies.forEach(hobby => {
            dataToSubmit.append('hobbies[]', hobby);
          });
        } else if (key === 'avatar' && this.formData.avatar) {
          dataToSubmit.append(key, this.formData.avatar);
        } else if (this.formData[key] !== null && this.formData[key] !== undefined) {
          dataToSubmit.append(key, this.formData[key]);
        }
      }
      dataToSubmit.append('_method', 'PUT'); // Laravel method spoofing

      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(this.updateRoute, {
          method: 'POST',
          body: dataToSubmit,
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });

        const responseData = await response.json();

        if (response.ok) {
          this.successMessage = responseData.message || 'Data berhasil disimpan!';
          if (responseData.avatar_url) {
            this.currentAvatarPreviewUrl = responseData.avatar_url;
            this.$emit('profileUpdated', responseData.profile);
          }
        } else {
          this.errorMessage = responseData.message || 'Terjadi kesalahan saat menyimpan data.';
          if (responseData.errors) {
            console.error('Validation errors:', responseData.errors);
          }
        }
      } catch (error) {
        console.error('Error submitting profile:', error);
        this.errorMessage = 'Terjadi kesalahan jaringan atau server.';
      } finally {
        this.isSaving = false;
        setTimeout(() => {
          this.successMessage = '';
          this.errorMessage = '';
        }, 3000);
      }
    }
  }
};
</script>

<style scoped>
/* Gaya yang sama seperti sebelumnya untuk badge dan progress bar */
.badge-animate {
  animation: bounce 1.2s infinite alternate;
}

@keyframes bounce {
  to {
    transform: translateY(-6px);
  }
}

.progress-animate {
  animation: progressBar 1.2s cubic-bezier(.4, 2, .6, 1) forwards;
}

@keyframes progressBar {
  from {
    width: 0;
  }
}

/* Kustomisasi gaya input field */
.form-input-custom {
  /* Gaya dasar yang mirip dengan Tailwind: */
  display: block; /* Agar mengambil lebar penuh */
  width: 100%;
  padding: 0.875rem 1.25rem; /* px-5 py-3.5 */
  font-size: 1.125rem; /* text-lg */
  line-height: 1.75rem; /* leading-7 */
  border-radius: 0.5rem; /* rounded-lg */
  outline: none; /* Hilangkan outline default browser */
  transition: all 0.2s ease-in-out; /* Transisi untuk efek halus */
  
  /* Border default: ungu muda (purple-300) dan 1px */
  border: 1px solid #d8b4fe; /* Tailwind purple-300 */

  /* Saat fokus: border menjadi 2px dan warna ungu lebih gelap (purple-600) */
  &:focus {
    border: 2px solid #9333ea; /* Tailwind purple-600 */
    box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.25); /* Optional: focus ring effect similar to focus:ring-2 */
    /* Jika Anda ingin efek 'ring' bawaan Tailwind, Anda bisa juga tambahkan di sini */
    /* box-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color); */
    /* Atau lebih sederhana, jika ingin menjaga `focus:ring-2` dari Tailwind: */
    /* Biarkan `focus:ring-2 focus:ring-purple-600` di class HTML dan hanya override `border` */
  }
}

/* Kustomisasi gaya checkbox untuk ukuran lebih besar */
.form-checkbox {
  appearance: none;
  background-color: #fff;
  margin: 0;
  font: inherit;
  color: currentColor;
  width: 1.25em; /* Slightly larger */
  height: 1.25em; /* Slightly larger */
  border: 0.175em solid #a78bfa; /* thicker border */
  border-radius: 0.25em; /* rounded-md */
  transform: translateY(-0.075em);
  display: grid;
  place-content: center;
}

.form-checkbox::before {
  content: "";
  width: 0.75em; /* Larger checkmark */
  height: 0.75em; /* Larger checkmark */
  transform: scale(0);
  transition: 120ms transform ease-in-out;
  box-shadow: inset 1em 1em var(--form-control-color, #8b5cf6); /* purple-500 */
  clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
}

.form-checkbox:checked::before {
  transform: scale(1);
}
</style>