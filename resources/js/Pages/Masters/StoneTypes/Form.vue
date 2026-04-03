<template>
  <AppLayout :title="stoneType?.id ? 'Edit Stone Type' : 'Create Stone Type'">
    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
          {{ stoneType?.id ? 'Edit Stone Type' : 'Create Stone Type' }}
        </h2>
      </div>

      <!-- Form -->
      <div class="bg-white shadow rounded-lg">
        <form @submit.prevent="submitForm" class="p-6 space-y-6">
          <!-- Code -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
            <input
              v-model="form.code"
              type="text"
              required
              maxlength="10"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.code }"
            />
            <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
          </div>

          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.name }"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <!-- Price Per Unit -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Price Per Unit (₹) *</label>
            <input
              v-model.number="form.price_per_unit"
              type="number"
              step="0.01"
              required
              min="0"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.price_per_unit }"
            />
            <p v-if="errors.price_per_unit" class="mt-1 text-sm text-red-600">{{ errors.price_per_unit }}</p>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.description }"
            ></textarea>
            <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
          </div>

          <!-- Active Status -->
          <div class="flex items-center">
            <input
              v-model="form.is_active"
              type="checkbox"
              :true-value="1"
              :false-value="0"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            />
            <label class="ml-2 block text-sm text-gray-900">
              Active
            </label>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-3 pt-4 border-t">
            <Link
              :href="route('stone-types.index')"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="processing"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              {{ processing ? 'Saving...' : 'Save Stone Type' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  stoneType: Object,
  errors: {
    type: Object,
    default: () => ({}),
  },
});

const processing = ref(false);

const form = ref({
  code: props.stoneType?.code || '',
  name: props.stoneType?.name || '',
  price_per_unit: props.stoneType?.price_per_unit || 0,
  description: props.stoneType?.description || '',
  is_active: props.stoneType?.is_active !== undefined ? props.stoneType.is_active : 1,
});

const submitForm = () => {
  processing.value = true;

  if (props.stoneType?.id) {
    router.put(route('stone-types.update', props.stoneType.id), form.value, {
      onFinish: () => {
        processing.value = false;
      },
    });
  } else {
    router.post(route('stone-types.store'), form.value, {
      onFinish: () => {
        processing.value = false;
      },
    });
  }
};
</script>
