<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Material/Card.vue';
import Button from '@/Components/Material/Button.vue';
import Input from '@/Components/Material/Input.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    inventoryItems: Array,
});

const form = useForm({
    items: [
        {
            inventory_item_id: '',
            quantity: '',
            notes: '',
        },
    ],
});

const addItem = () => {
    form.items.push({
        inventory_item_id: '',
        quantity: '',
        notes: '',
    });
};

const removeItem = (index) => {
    if (form.items.length > 1) {
        form.items.splice(index, 1);
    }
};

const getSelectedItem = (itemId) => {
    return props.inventoryItems.find(item => item.id === parseInt(itemId));
};

const getAvailableItems = (currentIndex) => {
    const selectedIds = form.items
        .filter((_, index) => index !== currentIndex)
        .map(item => parseInt(item.inventory_item_id))
        .filter(id => !isNaN(id));

    return props.inventoryItems.filter(item => !selectedIds.includes(item.id));
};

const formatQuantity = (quantity) => {
    const num = parseFloat(quantity);
    return num % 1 === 0 ? num.toFixed(0) : num.toFixed(2);
};

const submit = () => {
    form.post(route('inventory.store-add-stock'));
};
</script>

<template>
    <Head title="Add Stock" />

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
                <h1 class="text-2xl font-bold text-gray-900">Add Stock</h1>
            </div>
        </template>

        <div class="max-w-4xl mx-auto">
            <!-- Empty State -->
            <div v-if="inventoryItems.length === 0">
                <Card>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-gray-500 mb-4">No inventory items found</p>
                        <Link :href="route('inventory.create')">
                            <Button variant="primary">Create Your First Item</Button>
                        </Link>
                    </div>
                </Card>
            </div>

            <template v-else>
                <!-- Info Banner -->
                <div class="mb-6 p-4 bg-green-50 rounded-xl border border-green-100 flex items-start gap-4">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-green-900">Add Stock to Inventory</p>
                        <p class="text-sm text-green-700">Select items and enter the quantity to add. You can add stock to multiple items at once.</p>
                    </div>
                </div>

                <Card>
                    <form @submit.prevent="submit">
                        <!-- Items List -->
                        <div class="space-y-6">
                            <TransitionGroup
                                enter-active-class="transition ease-out duration-300"
                                enter-from-class="opacity-0 -translate-y-4"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition ease-in duration-200"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 -translate-y-4"
                            >
                                <div
                                    v-for="(item, index) in form.items"
                                    :key="index"
                                    class="relative p-6 bg-gray-50 rounded-xl border-2 border-gray-100"
                                >
                                    <!-- Item Header -->
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                </svg>
                                            </div>
                                            <h3 class="font-semibold text-gray-900">Stock Addition {{ index + 1 }}</h3>
                                        </div>
                                        <button
                                            v-if="form.items.length > 1"
                                            type="button"
                                            @click="removeItem(index)"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Form Fields -->
                                    <div class="grid gap-6 md:grid-cols-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Select Item <span class="text-red-500">*</span>
                                            </label>
                                            <select
                                                v-model="item.inventory_item_id"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                            >
                                                <option value="">Choose an item</option>
                                                <option
                                                    v-for="invItem in getAvailableItems(index)"
                                                    :key="invItem.id"
                                                    :value="invItem.id"
                                                >
                                                    {{ invItem.name }} ({{ formatQuantity(invItem.quantity) }} {{ invItem.unit?.abbreviation }})
                                                </option>
                                            </select>
                                            <p v-if="form.errors[`items.${index}.inventory_item_id`]" class="mt-1 text-sm text-red-500">
                                                {{ form.errors[`items.${index}.inventory_item_id`] }}
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Quantity to Add <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input
                                                    v-model="item.quantity"
                                                    type="number"
                                                    step="0.0001"
                                                    min="0.0001"
                                                    placeholder="0"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 pr-16"
                                                />
                                                <span
                                                    v-if="getSelectedItem(item.inventory_item_id)"
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500"
                                                >
                                                    {{ getSelectedItem(item.inventory_item_id)?.unit?.abbreviation }}
                                                </span>
                                            </div>
                                            <p v-if="form.errors[`items.${index}.quantity`]" class="mt-1 text-sm text-red-500">
                                                {{ form.errors[`items.${index}.quantity`] }}
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Notes (Optional)
                                            </label>
                                            <input
                                                v-model="item.notes"
                                                type="text"
                                                placeholder="e.g., Purchased from supplier"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                            />
                                            <p v-if="form.errors[`items.${index}.notes`]" class="mt-1 text-sm text-red-500">
                                                {{ form.errors[`items.${index}.notes`] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </TransitionGroup>
                        </div>

                        <!-- Add More Button -->
                        <div class="mt-6">
                            <button
                                type="button"
                                @click="addItem"
                                :disabled="form.items.length >= inventoryItems.length"
                                class="w-full p-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-green-500 hover:text-green-600 hover:bg-green-50 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Another Item
                            </button>
                        </div>

                        <!-- Actions -->
                        <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                            <Link :href="route('inventory.index')">
                                <Button variant="ghost">Cancel</Button>
                            </Link>
                            <Button
                                type="submit"
                                variant="success"
                                :loading="form.processing"
                                :disabled="form.processing"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                Add Stock
                            </Button>
                        </div>
                    </form>
                </Card>
            </template>
        </div>
    </AuthenticatedLayout>
</template>
