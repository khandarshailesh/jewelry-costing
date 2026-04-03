<template>
  <AppLayout :title="material?.id ? 'Edit Material' : 'Create Material'">
    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
          {{ material?.id ? 'Edit Material' : 'Create Material' }}
        </h2>
      </div>

      <!-- Form -->
      <div class="bg-white shadow rounded-lg">
        <form @submit.prevent="submitForm" class="p-6 space-y-6">
          <!-- Material Number -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Material Number *</label>
            <input
              v-model="form.material_number"
              type="text"
              required
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.material_number }"
            />
            <p v-if="errors.material_number" class="mt-1 text-sm text-red-600">{{ errors.material_number }}</p>
          </div>

          <!-- Material Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Material Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.name }"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <!-- Category -->
          <SearchableSelect
            v-model="form.category_id"
            :options="categories"
            label="Category"
            placeholder="Search and select category"
            :error="errors.category_id"
          />

          <div class="grid grid-cols-2 gap-4">
            <!-- Price (BHAV) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Price (BHAV) *</label>
              <input
                v-model.number="form.price"
                type="number"
                step="0.01"
                required
                min="0"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :class="{ 'border-red-500': errors.price }"
              />
              <p v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price }}</p>
            </div>

            <!-- Weight (RASS) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Weight (RASS) in grams</label>
              <input
                v-model.number="form.weight"
                type="number"
                step="0.0001"
                min="0"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :class="{ 'border-red-500': errors.weight }"
              />
              <p v-if="errors.weight" class="mt-1 text-sm text-red-600">{{ errors.weight }}</p>
            </div>
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
              :href="route('materials.index')"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="processing"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              {{ processing ? 'Saving...' : 'Save Material' }}
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
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
  material: Object,
  categories: Array,
  errors: {
    type: Object,
    default: () => ({}),
  },
});

const processing = ref(false);

const form = ref({
  material_number: props.material?.material_number || '',
  name: props.material?.name || '',
  category_id: props.material?.category_id || '',
  price: props.material?.price || 0,
  weight: props.material?.weight || 0,
  description: props.material?.description || '',
  is_active: props.material?.is_active !== undefined ? props.material.is_active : 1,
});

const submitForm = () => {
  processing.value = true;

  if (props.material?.id) {
    router.put(route('materials.update', props.material.id), form.value, {
      onFinish: () => {
        processing.value = false;
      },
    });
  } else {
    router.post(route('materials.store'), form.value, {
      onFinish: () => {
        processing.value = false;
      },
    });
  }
};
</script>
