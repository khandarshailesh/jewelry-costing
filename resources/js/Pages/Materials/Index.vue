<template>
  <AppLayout title="Materials">
    <div class="py-6">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Materials
          </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <Link :href="route('materials.create')" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Add Material
          </Link>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input
              v-model="filters.search"
              @input="filterMaterials"
              type="text"
              placeholder="Material number or name"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select
              v-model="filters.category_id"
              @change="filterMaterials"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select
              v-model="filters.is_active"
              @change="filterMaterials"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="">All</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Materials Table -->
      <DataTable
        :columns="columns"
        :data="materials"
        route-name="materials.index"
        :filters="filters"
        empty-message="No materials found"
      >
        <template #body="{ items }">
          <tr v-for="material in items" :key="material.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ material.material_number }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ material.name || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ material.category?.name || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ₹{{ parseFloat(material.price || 0).toFixed(4) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ parseFloat(material.weight || 0).toFixed(4) }} g
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="material.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ material.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end items-center space-x-3">
                  <Link :href="route('materials.edit', material.id)" class="text-blue-600 hover:text-blue-900" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </Link>
                  <button @click="deleteMaterial(material.id)" class="text-red-600 hover:text-red-900" title="Delete">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
        </template>
      </DataTable>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '@/Components/DataTable.vue';

const props = defineProps({
  materials: Object,
  categories: Array,
  filters: Object,
});

const columns = [
  { key: 'material_number', label: 'Material #' },
  { key: 'name', label: 'Name' },
  { key: 'category_id', label: 'Category' },
  { key: 'price', label: 'Price (BHAV)' },
  { key: 'weight', label: 'Weight (RASS)' },
  { key: 'is_active', label: 'Status' },
  { key: 'actions', label: 'Actions', sortable: false },
];

const filters = ref({
  search: props.filters?.search || '',
  category_id: props.filters?.category_id || '',
  is_active: props.filters?.is_active || '',
});

const filterMaterials = () => {
  router.get(route('materials.index'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const deleteMaterial = (id) => {
  if (confirm('Are you sure you want to delete this material?')) {
    router.delete(route('materials.destroy', id));
  }
};
</script>
