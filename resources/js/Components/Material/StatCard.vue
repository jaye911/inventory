<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: String,
    color: {
        type: String,
        default: 'indigo', // indigo, teal, green, orange, red
    },
    trend: {
        type: String,
        default: null, // up, down, null
    },
    trendValue: String,
});

const colorClasses = {
    indigo: {
        bg: 'bg-indigo-100',
        icon: 'text-indigo-600',
        trend: 'text-indigo-600',
    },
    teal: {
        bg: 'bg-teal-100',
        icon: 'text-teal-600',
        trend: 'text-teal-600',
    },
    green: {
        bg: 'bg-green-100',
        icon: 'text-green-600',
        trend: 'text-green-600',
    },
    orange: {
        bg: 'bg-orange-100',
        icon: 'text-orange-600',
        trend: 'text-orange-600',
    },
    red: {
        bg: 'bg-red-100',
        icon: 'text-red-600',
        trend: 'text-red-600',
    },
};
</script>

<template>
    <div class="bg-white rounded-xl shadow-md p-6 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">{{ title }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ value }}</p>
                <p v-if="trend" class="mt-2 flex items-center text-sm">
                    <span
                        :class="trend === 'up' ? 'text-green-600' : 'text-red-600'"
                        class="flex items-center"
                    >
                        <svg
                            v-if="trend === 'up'"
                            class="w-4 h-4 mr-1"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 mr-1"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        {{ trendValue }}
                    </span>
                </p>
            </div>
            <div
                class="p-4 rounded-full"
                :class="colorClasses[color].bg"
            >
                <slot name="icon">
                    <svg
                        class="w-8 h-8"
                        :class="colorClasses[color].icon"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </slot>
            </div>
        </div>
    </div>
</template>
