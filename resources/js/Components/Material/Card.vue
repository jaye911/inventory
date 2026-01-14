<script setup>
defineProps({
    hover: {
        type: Boolean,
        default: false,
    },
    padding: {
        type: String,
        default: 'p-6',
    },
});
</script>

<template>
    <div
        class="bg-white rounded-lg shadow-md transition-all duration-300"
        :class="[
            padding,
            hover ? 'hover:shadow-xl hover:-translate-y-1 cursor-pointer' : ''
        ]"
    >
        <slot />
    </div>
</template>
