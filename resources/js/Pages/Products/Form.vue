<template>
  <AppLayout :title="product?.id ? 'Edit Product' : 'Create Product'">
    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
          {{ product?.id ? 'Edit Product' : 'Create Product' }}
        </h2>
      </div>

      <!-- Form -->
      <div class="bg-white shadow rounded-lg">
        <form @submit.prevent="submitForm" class="p-6 space-y-6">
          <!-- Product Number -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Number *</label>
            <input
              v-model="form.product_number"
              type="text"
              required
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.product_number }"
            />
            <p v-if="errors.product_number" class="mt-1 text-sm text-red-600">{{ errors.product_number }}</p>
          </div>

          <!-- Product Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
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
            required
            :error="errors.category_id"
          />

          <!-- Weight (RASS) -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Weight (RASS) in grams *</label>
            <input
              v-model.number="form.weight"
              type="number"
              step="0.0001"
              required
              min="0"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              :class="{ 'border-red-500': errors.weight }"
            />
            <p v-if="errors.weight" class="mt-1 text-sm text-red-600">{{ errors.weight }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <!-- MINO Count -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">MINO Count</label>
              <input
                v-model.number="form.mino_count"
                type="number"
                min="0"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :class="{ 'border-red-500': errors.mino_count }"
              />
              <p v-if="errors.mino_count" class="mt-1 text-sm text-red-600">{{ errors.mino_count }}</p>
            </div>

            <!-- Stone Count -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Stone Count</label>
              <input
                v-model.number="form.stone_count"
                type="number"
                min="0"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :class="{ 'border-red-500': errors.stone_count }"
              />
              <p v-if="errors.stone_count" class="mt-1 text-sm text-red-600">{{ errors.stone_count }}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <!-- Chips Count -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Chips Count</label>
              <input
                v-model.number="form.chips_count"
                type="number"
                min="0"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :class="{ 'border-red-500': errors.chips_count }"
              />
              <p v-if="errors.chips_count" class="mt-1 text-sm text-red-600">{{ errors.chips_count }}</p>
            </div>

            <!-- OXO Factor -->
            <SearchableSelect
              v-model.number="form.oxo_factor"
              :options="oxoFactors"
              label="OXO Factor"
              placeholder="Select OXO factor"
              label-key="name"
              value-key="value"
              :error="errors.oxo_factor"
            />
          </div>

          <!-- Stone Type -->
          <SearchableSelect
            v-model="form.stone_type"
            :options="stoneTypes"
            label="Stone Type"
            placeholder="Search and select stone type"
            label-key="name"
            value-key="code"
            required
            :error="errors.stone_type"
          />

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
              :href="route('products.index')"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="processing"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              {{ processing ? 'Saving...' : 'Save Product' }}
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
  product: Object,
  categories: Array,
  stoneTypes: Array,
  oxoFactors: Array,
  errors: {
    type: Object,
    default: () => ({}),
  },
});

const processing = ref(false);

const form = ref({
  product_number: props.product?.product_number || '',
  name: props.product?.name || '',
  category_id: props.product?.category_id || '',
  weight: props.product?.weight || 0,
  mino_count: props.product?.mino_count || 0,
  stone_count: props.product?.stone_count || 0,
  chips_count: props.product?.chips_count || 0,
  oxo_factor: props.product?.oxo_factor || 0,
  stone_type: props.product?.stone_type || 'k',
  description: props.product?.description || '',
  is_active: props.product?.is_active !== undefined ? props.product.is_active : 1,
});

const submitForm = () => {
  processing.value = true;

  if (props.product?.id) {
    router.put(route('products.update', props.product.id), form.value, {
      onFinish: () => {
        processing.value = false;
      },
    });
  } else {
    router.post(route('products.store'), form.value, {
      onFinish: () => {
        processing.value = false;
      },
    });
  }
};
</script>
