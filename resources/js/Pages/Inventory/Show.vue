<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Material/Card.vue';
import Button from '@/Components/Material/Button.vue';
import Modal from '@/Components/Material/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    item: Object,
    transactions: Object,
});

const showDeleteModal = ref(false);

const deleteItem = () => {
    router.delete(route('inventory.destroy', props.item.id));
};

const formatQuantity = (quantity) => {
    const num = parseFloat(quantity);
    return num % 1 === 0 ? num.toFixed(0) : num.toFixed(2);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStockStatus = (quantity) => {
    const qty = parseFloat(quantity);
    if (qty <= 0) return { label: 'Out of Stock', class: 'bg-red-100 text-red-800', color: 'red' };
    if (qty < 10) return { label: 'Low Stock', class: 'bg-orange-100 text-orange-800', color: 'orange' };
    return { label: 'In Stock', class: 'bg-green-100 text-green-800', color: 'green' };
};

const status = getStockStatus(props.item.quantity);
</script>

<template>
    <Head :title="item.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link
                    :href="route('inventory.index')"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h1 class="text-2xl font-bold text-gray-900">{{ item.name }}</h1>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Item Details - Left Column -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Main Info Card -->
                <Card>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ item.name }}</h2>
                        <p class="text-gray-500 mt-1">{{ item.unit?.name }}</p>

                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                :class="status.class"
                            >
                                {{ status.label }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Current Stock</p>
                            <p class="mt-2">
                                <span
                                    class="text-4xl font-bold"
                                    :class="{
                                        'text-red-600': status.color === 'red',
                                        'text-orange-600': status.color === 'orange',
                                        'text-green-600': status.color === 'green',
                                    }"
                                >
                                    {{ formatQuantity(item.quantity) }}
                                </span>
                                <span class="text-lg text-gray-500 ml-1">{{ item.unit?.abbreviation }}</span>
                            </p>
                        </div>
                    </div>

                    <div v-if="item.description" class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">Description</p>
                        <p class="text-gray-700">{{ item.description }}</p>
                    </div>
                </Card>

                <!-- Actions Card -->
                <Card>
                    <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <Link :href="route('inventory.edit', item.id)" class="block">
                            <Button variant="outline" class="w-full justify-start">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Details
                            </Button>
                        </Link>
                        <Link :href="route('inventory.add-stock')" class="block">
                            <Button variant="success" class="w-full justify-start">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                Add Stock
                            </Button>
                        </Link>
                        <Link :href="route('inventory.deduct-stock')" class="block">
                            <Button variant="warning" class="w-full justify-start">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                </svg>
                                Deduct Stock
                            </Button>
                        </Link>
                        <Button
                            variant="danger"
                            class="w-full justify-start"
                            @click="showDeleteModal = true"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Item
                        </Button>
                    </div>
                </Card>
            </div>

            <!-- Transaction History - Right Column -->
            <div class="lg:col-span-2">
                <Card>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Transaction History</h3>
                        <span class="text-sm text-gray-500">
                            {{ transactions.total }} total
                        </span>
                    </div>

                    <div v-if="transactions.data.length === 0" class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500">No transactions yet</p>
                    </div>

                    <div v-else class="flow-root">
                        <ul class="-mb-8">
                            <li v-for="(transaction, index) in transactions.data" :key="transaction.id">
                                <div class="relative pb-8">
                                    <!-- Timeline connector -->
                                    <span
                                        v-if="index !== transactions.data.length - 1"
                                        class="absolute left-6 top-6 -ml-px h-full w-0.5 bg-gray-200"
                                    />

                                    <div class="relative flex items-start space-x-4">
                                        <!-- Icon -->
                                        <div
                                            class="relative flex h-12 w-12 items-center justify-center rounded-xl shadow-sm"
                                            :class="transaction.type === 'addition' ? 'bg-green-100' : 'bg-red-100'"
                                        >
                                            <svg
                                                class="h-6 w-6"
                                                :class="transaction.type === 'addition' ? 'text-green-600' : 'text-red-600'"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    v-if="transaction.type === 'addition'"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7 11l5-5m0 0l5 5m-5-5v12"
                                                />
                                                <path
                                                    v-else
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 13l-5 5m0 0l-5-5m5 5V6"
                                                />
                                            </svg>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0 py-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-medium text-gray-900">
                                                    {{ transaction.type === 'addition' ? 'Stock Added' : 'Stock Deducted' }}
                                                </p>
                                                <span
                                                    class="text-lg font-bold"
                                                    :class="transaction.type === 'addition' ? 'text-green-600' : 'text-red-600'"
                                                >
                                                    {{ transaction.type === 'addition' ? '+' : '-' }}{{ formatQuantity(transaction.quantity) }}
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center gap-4 text-sm text-gray-500">
                                                <span>{{ formatDate(transaction.created_at) }}</span>
                                                <span v-if="transaction.user?.name">
                                                    by {{ transaction.user.name }}
                                                </span>
                                            </div>
                                            <p v-if="transaction.notes" class="mt-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                                {{ transaction.notes }}
                                            </p>
                                            <div class="mt-2 flex items-center gap-4 text-xs text-gray-400">
                                                <span>Before: {{ formatQuantity(transaction.quantity_before) }}</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                                <span>After: {{ formatQuantity(transaction.quantity_after) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="transactions.links.length > 3"
                        class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-between"
                    >
                        <p class="text-sm text-gray-600">
                            Showing {{ transactions.from }} to {{ transactions.to }} of {{ transactions.total }}
                        </p>
                        <div class="flex gap-1">
                            <template v-for="link in transactions.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    v-html="link.label"
                                    class="px-3 py-1 text-sm rounded-lg transition-colors"
                                    :class="{
                                        'bg-indigo-600 text-white': link.active,
                                        'text-gray-600 hover:bg-gray-100': !link.active,
                                    }"
                                />
                                <span
                                    v-else
                                    v-html="link.label"
                                    class="px-3 py-1 text-sm text-gray-400"
                                />
                            </template>
                        </div>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="sm">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Delete Item</h3>
                <p class="text-gray-600 text-center mb-6">
                    Are you sure you want to delete <strong>{{ item.name }}</strong>? All transaction history will also be deleted. This action cannot be undone.
                </p>
                <div class="flex gap-3">
                    <Button variant="ghost" class="flex-1" @click="showDeleteModal = false">
                        Cancel
                    </Button>
                    <Button variant="danger" class="flex-1" @click="deleteItem">
                        Delete
                    </Button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
