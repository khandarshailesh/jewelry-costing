<template>
  <AppLayout title="OXO Factors">
    <div class="py-6">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            OXO Factors Master
          </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <Link :href="route('oxo-factors.create')" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Add OXO Factor
          </Link>
        </div>
      </div>

      <!-- OXO Factors Table -->
      <DataTable
        :columns="columns"
        :data="oxoFactors"
        route-name="oxo-factors.index"
        :filters="filters"
        empty-message="No OXO factors found"
      >
        <template #body="{ items }">
          <tr v-for="oxoFactor in items" :key="oxoFactor.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ oxoFactor.value }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ oxoFactor.name }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ oxoFactor.description || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="oxoFactor.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ oxoFactor.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end items-center space-x-3">
                  <Link :href="route('oxo-factors.edit', oxoFactor.id)" class="text-blue-600 hover:text-blue-900" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </Link>
                  <button @click="deleteOxoFactor(oxoFactor.id)" class="text-red-600 hover:text-red-900" title="Delete">
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
  oxoFactors: Object,
  filters: Object,
});

const columns = [
  { key: 'value', label: 'Value' },
  { key: 'name', label: 'Name' },
  { key: 'description', label: 'Description', sortable: false },
  { key: 'is_active', label: 'Status' },
  { key: 'actions', label: 'Actions', sortable: false },
];

const filters = ref(props.filters || {});

const deleteOxoFactor = (id) => {
  if (confirm('Are you sure you want to delete this OXO factor?')) {
    router.delete(route('oxo-factors.destroy', id));
  }
};
</script>
