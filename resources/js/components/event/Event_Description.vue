<script setup>
import { computed } from 'vue';
const props = defineProps({
    event: {
        type: Object,
        required: true,
        // Provide a default or example structure for clarity
        default: () => ({
            title: 'Default Event Title',
            image: '/images/default-event.jpg', // Placeholder for event-level image
            category: 'general',
            price: 0,
            formatted_date: 'N/A', // Fallback for date
            description: {
                title: 'Default Event Description Title',
                image: '', // Can be a specific image for the description
                overview: 'This is a general overview of the event. It provides a brief summary of what the event is about and its main purpose.',
                what_youll_learn: [
                    "Key concepts and foundational knowledge",
                    "Practical skills and hands-on exercises",
                    "Best practices and industry insights"
                ],
                terms_conditions: [
                    "All participants must register in advance.",
                    "Cancellation policy applies as per event guidelines.",
                    "Adherence to code of conduct is mandatory."
                ],
                final_price: 150000,
                price_original: 250000, // Example of original price
                price_discounted: 150000, // Example of discounted price (matches final_price for simplicity)
                speaker_name: 'Dr. Jane Doe',
                speaker_title: 'Lead Scientist @TechSolutions',
                dates: ['2025-10-05'], // Example with single date in array
                time: '09.00 WIB - 17.00 WIB',
                location: 'Online via Zoom',
                includes: [
                    "Digital Certificate of Completion",
                    "Comprehensive Course Materials (PDF)",
                    "Access to exclusive online community"
                ]
            }
        })
    }
});

// Computed property for the main title
const eventTitle = computed(() => props.event.description?.title ?? props.event.title);

// Computed property for the image source with fallback logic
const eventImage = computed(() => {
    if (props.event.description?.image) {
        // Check if it's a direct asset path (e.g., 'image/illustration.png')
        // Or if it's a storage path (e.g., 'path/to/stored/image.jpg')
        // Adjust '/src/assets/' and '/storage/' based on your actual project setup
        return props.event.description.image.startsWith('image/')
            ? `/src/assets/${props.event.description.image}`
            : `/storage/${props.event.description.image}`;
    } else if (props.event.image) {
        return `/storage/${props.event.image}`;
    }
    // Fallback to a default image if no image is provided
    return '/src/assets/images/default-event.jpg'; // Ensure this path exists in your Vue assets
});

// Computed property for formatting the category
const eventCategory = computed(() => {
    return props.event.category ?
           props.event.category.replace(/-/g, ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ') :
           'Uncategorized';
});

// Computed property for formatting the final price
const finalPrice = computed(() => props.event.description?.final_price ?? props.event.price ?? 0);

// Computed property for formatting the original price
const originalPrice = computed(() => props.event.description?.price_original);

// Computed property for formatting dates
const formattedDates = computed(() => {
    if (props.event.description?.dates && props.event.description.dates.length > 0) {
        return props.event.description.dates.map(dateItem => {
            const dateValue = typeof dateItem === 'object' ? (dateItem.date ?? Object.values(dateItem)[0]) : dateItem;
            // Use Intl.DateTimeFormat for robust date formatting
            return new Date(dateValue).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        });
    }
    // Fallback to event.formatted_date if description dates are empty
    if (props.event.formatted_date) {
        return [props.event.formatted_date];
    }
    return [];
});
</script>

<template>
    <section class="bg-gray-100 text-gray-800 font-sans">
        <div class="max-w-7xl mx-auto px-4 py-5">
            <div class="mb-5">
                <nav class="text-sm text-gray-600 mb-4">
                    <a href="/events" class="hover:text-purple-600">Events</a>
                    <span class="mx-2">></span>
                    <span class="text-gray-800">{{ eventTitle }}</span>
                </nav>
            </div>

            <div class="mb-5">
                <h1 class="text-3xl md:text-4xl font-bold text-black-900 mb-5">
                    {{ eventTitle }}
                </h1>
            </div>

            <div class="flex flex-col md:flex-row gap-6 mb-16">
                <div class="md:w-2/3">
                    <div>
                        <img
                            :src="eventImage"
                            alt="Event Image"
                            class="w-full h-96 rounded-xl object-cover"
                        />
                        <div class="mt-4 p-3">
                            <span class="inline-block bg-[#564AB1] text-white px-7 py-3 rounded-full text-sm font-medium">
                                {{ eventCategory }}
                            </span>
                        </div>
                    </div>

                    <div v-if="event.description">
                        <div v-if="event.description.overview" class="bg-white rounded-lg p-5 border border-gray-200 mt-5">
                            <h2 class="text-xl font-semibold text-purple-600 mb-4">Overview</h2>
                            <p class="mb-3">{{ event.description.overview }}</p>
                        </div>

                        <div v-if="event.description.what_youll_learn && event.description.what_youll_learn.length > 0"
                             class="bg-white rounded-lg p-5 border border-gray-200 mt-5">
                            <h2 class="text-xl font-semibold text-purple-600 mb-4">What you'll learn</h2>
                            <ul class="list-disc pl-5">
                                <li v-for="(item, index) in event.description.what_youll_learn" :key="index" class="mb-2">
                                    {{ typeof item === 'object' ? (item.item ?? item.term ?? item.date ?? Object.values(item)[0]) : item }}
                                </li>
                            </ul>
                        </div>

                        <div v-if="event.description.terms_conditions && event.description.terms_conditions.length > 0"
                             class="bg-white rounded-lg p-5 border border-gray-200 mt-5">
                            <h2 class="text-xl font-semibold text-purple-600 mb-4">Terms & Conditions</h2>
                            <ul class="list-disc pl-5">
                                <li v-for="(term, index) in event.description.terms_conditions" :key="index" class="mb-2">
                                    {{ typeof term === 'object' ? (term.term ?? term.item ?? term.date ?? Object.values(term)[0]) : term }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="md:w-1/3">
                    <div class="bg-white rounded-lg p-5 border border-gray-200">
                        <h2 class="text-2xl font-bold text-black-900 mb-1">
                            Rp {{ finalPrice.toLocaleString('id-ID') }}
                        </h2>
                        <p v-if="originalPrice !== null && originalPrice > finalPrice" class="text-gray-500 line-through text-base mb-4">
                            Rp {{ originalPrice.toLocaleString('id-ID') }}
                        </p>
                        <a href="/payment" class="block bg-[#564AB1] text-white text-center py-3 px-4 rounded-lg font-bold w-full hover:bg-[#463C8F] transition">
                            Daftar Event
                        </a>
                    </div>

                    <div v-if="event.description && (event.description.speaker_name || event.description.speaker_title)"
                         class="bg-white rounded-lg p-5 border border-gray-200 mt-5">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                                <UserIcon class="h-6 w-6 text-gray-500" />
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-black-900 m-0">
                                    {{ event.description.speaker_name ?? 'Speaker Name' }}
                                </h3>
                                <p class="text-gray-500 text-sm m-0">
                                    {{ event.description.speaker_title ?? 'Speaker Title' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-5 border border-gray-200 mt-5">
                        <h3 class="text-xl font-semibold text-[#564AB1] mb-4">Event Information</h3>

                        <div class="ps-6 mb-4">
                            <h4 class="text-base font-medium text-[#564AB1] mb-2">Tanggal</h4>
                            <p v-for="(date, index) in formattedDates" :key="index" class="text-gray-500">{{ date }}</p>
                        </div>

                        <div v-if="event.description && event.description.time" class="ps-6 mb-4">
                            <h4 class="text-base font-medium text-[#564AB1] mb-2">Waktu</h4>
                            <p class="text-gray-500">{{ event.description.time }}</p>
                        </div>

                        <div class="ps-6 mb-4">
                            <h4 class="text-base font-medium text-[#564AB1] mb-2">Lokasi</h4>
                            <p class="text-gray-500">{{ event.description?.location ?? event.location }}</p>
                        </div>
                    </div>

                    <div v-if="event.description && event.description.includes && event.description.includes.length > 0"
                         class="bg-white rounded-lg p-5 border border-gray-200 mt-5">
                        <h3 class="text-xl font-semibold text-[#564AB1] mb-4">This Event Includes</h3>
                        <ul class="list-disc pl-5">
                            <li v-for="(include, index) in event.description.includes" :key="index" class="mb-2">
                                {{ typeof include === 'object' ? (include.item ?? Object.values(include)[0]) : include }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Any component-specific styles can go here. 
   'scoped' attribute ensures styles only apply to this component. */
</style>