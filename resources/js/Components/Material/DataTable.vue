<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        default: () => [],
    },
    sortable: {
        type: Boolean,
        default: true,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['sort', 'row-click']);

const sortColumn = ref(null);
const sortDirection = ref('asc');

const handleSort = (column) => {
    if (!props.sortable || !column.sortable) return;

    if (sortColumn.value === column.key) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column.key;
        sortDirection.value = 'asc';
    }

    emit('sort', { column: sortColumn.value, direction: sortDirection.value });
};

const handleRowClick = (row) => {
    emit('row-click', row);
};
</script>

<template>
    <div class="overflow-hidden bg-white rounded-xl shadow-md">
        <!-- Loading overlay -->
        <div v-if="loading" class="absolute inset-0 bg-white/80 flex items-center justify-center z-10">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            :class="[
                                column.sortable && sortable ? 'cursor-pointer hover:bg-gray-100 select-none' : '',
                                column.align === 'right' ? 'text-right' : column.align === 'center' ? 'text-center' : 'text-left',
                            ]"
                            @click="handleSort(column)"
                        >
                            <div class="flex items-center gap-2" :class="column.align === 'right' ? 'justify-end' : ''">
                                <span>{{ column.label }}</span>
                                <span v-if="column.sortable && sortable" class="flex flex-col">
                                    <svg
                                        class="w-3 h-3 transition-colors"
                                        :class="sortColumn === column.key && sortDirection === 'asc' ? 'text-indigo-600' : 'text-gray-300'"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M7 14l5-5 5 5H7z" />
                                    </svg>
                                    <svg
                                        class="w-3 h-3 -mt-1 transition-colors"
                                        :class="sortColumn === column.key && sortDirection === 'desc' ? 'text-indigo-600' : 'text-gray-300'"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M7 10l5 5 5-5H7z" />
                                    </svg>
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="(row, index) in data"
                        :key="row.id || index"
                        class="hover:bg-gray-50 transition-colors cursor-pointer"
                        @click="handleRowClick(row)"
                    >
                        <td
                            v-for="column in columns"
                            :key="column.key"
                            class="px-6 py-4 whitespace-nowrap text-sm"
                            :class="column.align === 'right' ? 'text-right' : column.align === 'center' ? 'text-center' : 'text-left'"
                        >
                            <slot :name="'cell-' + column.key" :row="row" :value="row[column.key]">
                                {{ row[column.key] }}
                            </slot>
                        </td>
                    </tr>
                    <tr v-if="data.length === 0 && !loading">
                        <td :colspan="columns.length" class="px-6 py-12 text-center">
                            <slot name="empty">
                                <div class="text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">No data available</p>
                                </div>
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer slot for pagination -->
        <div v-if="$slots.footer" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <slot name="footer" />
        </div>
    </div>
</template>
