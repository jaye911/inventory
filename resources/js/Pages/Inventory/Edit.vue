<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Material/Card.vue';
import Button from '@/Components/Material/Button.vue';
import Input from '@/Components/Material/Input.vue';
import Select from '@/Components/Material/Select.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    item: Object,
    units: Array,
});

const form = useForm({
    name: props.item.name,
    description: props.item.description || '',
    unit_id: props.item.unit_id,
});

const unitOptions = computed(() =>
    props.units.map(unit => ({
        value: unit.id,
        label: `${unit.name} (${unit.abbreviation})`,
    }))
);

const formatQuantity = (quantity) => {
    const num = parseFloat(quantity);
    return num % 1 === 0 ? num.toFixed(0) : num.toFixed(2);
};

const submit = () => {
    form.patch(route('inventory.update', props.item.id));
};
</script>

<template>
    <Head :title="'Edit ' + item.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link
                    :href="route('inventory.show', item.id)"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h1 class="text-2xl font-bold text-gray-900">Edit Item</h1>
            </div>
        </template>

        <div class="max-w-2xl mx-auto">
            <!-- Info Banner -->
            <div class="mb-6 p-4 bg-indigo-50 rounded-xl border border-indigo-100 flex items-start gap-4">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-indigo-900">Current Stock</p>
                    <p class="text-indigo-700">
                        <span class="text-xl font-bold">{{ formatQuantity(item.quantity) }}</span>
                        <span class="ml-1">{{ item.unit?.abbreviation }}</span>
                    </p>
                    <p class="text-sm text-indigo-600 mt-1">
                        To change quantity, use
                        <Link :href="route('inventory.add-stock')" class="underline font-medium">Add Stock</Link>
                        or
                        <Link :href="route('inventory.deduct-stock')" class="underline font-medium">Deduct Stock</Link>
                    </p>
                </div>
            </div>

            <Card>
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <Input
                            v-model="form.name"
                            label="Item Name"
                            placeholder="Enter item name"
                            :error="form.errors.name"
                            required
                        />

                        <Select
                            v-model="form.unit_id"
                            label="Unit"
                            :options="unitOptions"
                            placeholder="Select unit"
                            :error="form.errors.unit_id"
                            required
                        />

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                placeholder="Optional description"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none"
                            ></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">
                                {{ form.errors.description }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <Link :href="route('inventory.show', item.id)">
                            <Button variant="ghost">Cancel</Button>
                        </Link>
                        <Button
                            type="submit"
                            variant="primary"
                            :loading="form.processing"
                            :disabled="form.processing"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Item
                        </Button>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
