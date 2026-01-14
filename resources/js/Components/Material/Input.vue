<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    type: {
        type: String,
        default: 'text',
    },
    error: String,
    placeholder: String,
    disabled: Boolean,
    required: Boolean,
});

const emit = defineEmits(['update:modelValue']);

const isFocused = ref(false);
const inputRef = ref(null);

const isFloating = computed(() => {
    return isFocused.value || props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined;
});

const handleInput = (event) => {
    emit('update:modelValue', event.target.value);
};

const focus = () => {
    inputRef.value?.focus();
};

defineExpose({ focus });
</script>

<template>
    <div class="relative">
        <div
            class="relative border-2 rounded-lg transition-all duration-200"
            :class="[
                error ? 'border-red-500' : isFocused ? 'border-indigo-600' : 'border-gray-300',
                disabled ? 'bg-gray-100' : 'bg-white',
            ]"
        >
            <label
                v-if="label"
                class="absolute left-3 transition-all duration-200 pointer-events-none"
                :class="[
                    isFloating
                        ? '-top-2.5 text-xs px-1 bg-white'
                        : 'top-3 text-base',
                    error ? 'text-red-500' : isFocused ? 'text-indigo-600' : 'text-gray-500',
                ]"
            >
                {{ label }}
                <span v-if="required" class="text-red-500">*</span>
            </label>
            <input
                ref="inputRef"
                :type="type"
                :value="modelValue"
                :placeholder="isFloating ? placeholder : ''"
                :disabled="disabled"
                @input="handleInput"
                @focus="isFocused = true"
                @blur="isFocused = false"
                class="w-full px-3 py-3 bg-transparent outline-none text-gray-900 disabled:cursor-not-allowed"
                :class="{ 'pr-10': error }"
            />
            <div v-if="error" class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <p v-if="error" class="mt-1 text-sm text-red-500">{{ error }}</p>
    </div>
</template>
