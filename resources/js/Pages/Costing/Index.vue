<template>
  <AppLayout title="Costing History">
    <div class="py-6">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Costing History
          </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <Link :href="route('costing.calculator')" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            New Calculation
          </Link>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Product</label>
            <input
              v-model="filters.search"
              @input="filterCostings"
              type="text"
              placeholder="Product number"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
            <input
              v-model="filters.date_from"
              @change="filterCostings"
              type="date"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
            <input
              v-model="filters.date_to"
              @change="filterCostings"
              type="date"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
            <button
              @click="clearFilters"
              class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Costings Table -->
      <DataTable
        :columns="columns"
        :data="costings"
        route-name="costing.history"
        :filters="tableFilters"
        empty-message="No costing history found"
      >
        <template #body="{ items }">
          <tr v-for="costing in items" :key="costing.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(costing.created_at) }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ costing.product?.product_number || '-' }}
                <div v-if="costing.product?.name" class="text-xs text-gray-500">{{ costing.product.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ costing.weight }} g
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ₹{{ parseFloat(costing.metal_cost).toFixed(4) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ₹{{ parseFloat(costing.stone_cost).toFixed(4) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ₹{{ parseFloat(costing.gross_total).toFixed(4) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ₹{{ parseFloat(costing.final_price).toFixed(4) }}
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
  costings: Object,
  tableFilters: Object,
});

const columns = [
  { key: 'created_at', label: 'Date' },
  { key: 'product_id', label: 'Product', sortable: false },
  { key: 'weight', label: 'Weight' },
  { key: 'metal_cost', label: 'Metal Cost' },
  { key: 'stone_cost', label: 'Stone Cost' },
  { key: 'gross_total', label: 'Gross Total' },
  { key: 'final_price', label: 'Final Price' },
];

const filters = ref({
  search: '',
  date_from: '',
  date_to: '',
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const filterCostings = () => {
  router.get(route('costing.history'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const clearFilters = () => {
  filters.value = {
    search: '',
    date_from: '',
    date_to: '',
  };
  filterCostings();
};
</script>
