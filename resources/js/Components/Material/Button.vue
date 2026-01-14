<script setup>
import { ref } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary', // primary, secondary, success, warning, danger, outline
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
    icon: {
        type: Boolean,
        default: false,
    },
    fab: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const buttonRef = ref(null);
const ripples = ref([]);

const variantClasses = {
    primary: 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500',
    secondary: 'bg-teal-600 text-white hover:bg-teal-700 focus:ring-teal-500',
    success: 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
    warning: 'bg-orange-500 text-white hover:bg-orange-600 focus:ring-orange-500',
    danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    outline: 'bg-transparent border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 focus:ring-indigo-500',
    ghost: 'bg-transparent text-gray-600 hover:bg-gray-100 focus:ring-gray-500',
};

const sizeClasses = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base',
};

const createRipple = (event) => {
    if (props.disabled || props.loading) return;

    const button = buttonRef.value;
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    const ripple = {
        id: Date.now(),
        x,
        y,
        size,
    };

    ripples.value.push(ripple);

    setTimeout(() => {
        ripples.value = ripples.value.filter(r => r.id !== ripple.id);
    }, 600);
};
</script>

<template>
    <button
        ref="buttonRef"
        @click="createRipple"
        :disabled="disabled || loading"
        class="relative overflow-hidden font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
        :class="[
            variantClasses[variant],
            fab ? 'w-14 h-14 rounded-full shadow-lg hover:shadow-xl p-0 flex items-center justify-center' : sizeClasses[size],
            icon ? 'p-2' : '',
        ]"
    >
        <!-- Ripple effects -->
        <span
            v-for="ripple in ripples"
            :key="ripple.id"
            class="absolute bg-white/30 rounded-full animate-ripple pointer-events-none"
            :style="{
                left: ripple.x + 'px',
                top: ripple.y + 'px',
                width: ripple.size + 'px',
                height: ripple.size + 'px',
            }"
        />

        <!-- Loading spinner -->
        <span v-if="loading" class="absolute inset-0 flex items-center justify-center">
            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
        </span>

        <!-- Button content -->
        <span :class="{ 'opacity-0': loading }">
            <slot />
        </span>
    </button>
</template>

<style scoped>
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.animate-ripple {
    animation: ripple 0.6s linear;
}
</style>
