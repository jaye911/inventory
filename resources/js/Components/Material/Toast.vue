<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
    show: Boolean,
    message: String,
    type: {
        type: String,
        default: 'success', // success, error, warning, info
    },
    duration: {
        type: Number,
        default: 4000,
    },
});

const emit = defineEmits(['close']);

const isVisible = ref(false);

const typeConfig = {
    success: {
        bg: 'bg-green-600',
        icon: 'M5 13l4 4L19 7',
    },
    error: {
        bg: 'bg-red-600',
        icon: 'M6 18L18 6M6 6l12 12',
    },
    warning: {
        bg: 'bg-orange-500',
        icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    },
    info: {
        bg: 'bg-indigo-600',
        icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        isVisible.value = true;
        if (props.duration > 0) {
            setTimeout(() => {
                close();
            }, props.duration);
        }
    }
});

const close = () => {
    isVisible.value = false;
    setTimeout(() => {
        emit('close');
    }, 300);
};

onMounted(() => {
    if (props.show) {
        isVisible.value = true;
        if (props.duration > 0) {
            setTimeout(() => {
                close();
            }, props.duration);
        }
    }
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="transform -translate-y-full opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-full opacity-0"
        >
            <div
                v-if="show && isVisible"
                class="fixed top-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white min-w-[300px]"
                :class="typeConfig[type].bg"
            >
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="typeConfig[type].icon" />
                </svg>
                <p class="flex-1 text-sm font-medium">{{ message }}</p>
                <button
                    @click="close"
                    class="flex-shrink-0 p-1 rounded-full hover:bg-white/20 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </Transition>
    </Teleport>
</template>
