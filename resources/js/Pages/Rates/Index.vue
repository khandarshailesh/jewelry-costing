<template>
  <AppLayout title="Rate Configurations">
    <div class="py-6">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Rate Configurations
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Manage pricing rates and markup percentages for costing calculations
          </p>
        </div>
      </div>

      <!-- Search Filter -->
      <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input
              v-model="searchFilters.search"
              @input="filterRates"
              type="text"
              placeholder="Rate name or code"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select
              v-model="searchFilters.rate_type"
              @change="filterRates"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="">All Types</option>
              <option value="multiplier">Multiplier</option>
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select
              v-model="searchFilters.is_active"
              @change="filterRates"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
              <option value="">All</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Rates Table -->
      <DataTable
        :columns="columns"
        :data="rates"
        route-name="rates.index"
        :filters="filters"
        empty-message="No rates configured"
      >
        <template #body="{ items }">
          <tr v-for="rate in items" :key="rate.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 text-sm font-medium text-gray-900">
                {{ rate.name }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                <code class="px-2 py-1 bg-gray-100 rounded text-xs">{{ rate.code }}</code>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <span :class="{
                  'bg-blue-100 text-blue-800': rate.rate_type === 'multiplier',
                  'bg-green-100 text-green-800': rate.rate_type === 'fixed',
                  'bg-purple-100 text-purple-800': rate.rate_type === 'percentage'
                }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ rate.rate_type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span v-if="rate.rate_type === 'multiplier'">{{ rate.rate_value }}×</span>
                <span v-else-if="rate.rate_type === 'percentage'">{{ (rate.rate_value * 100).toFixed(1) }}%</span>
                <span v-else>₹{{ rate.rate_value }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(rate.effective_from) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="rate.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ rate.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="editRate(rate)" class="text-blue-600 hover:text-blue-900" title="Edit">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </td>
            </tr>
        </template>
      </DataTable>

      <!-- Edit Rate Modal -->
      <div v-if="editingRate" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Rate</h3>

          <form @submit.prevent="saveRate" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Rate Name</label>
              <input
                v-model="editForm.name"
                type="text"
                required
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
              <input
                v-model="editForm.code"
                type="text"
                required
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Value *</label>
              <input
                v-model.number="editForm.rate_value"
                type="number"
                step="0.0001"
                required
                min="0"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
              <p class="mt-1 text-xs text-gray-500">
                <span v-if="editForm.rate_type === 'multiplier'">Enter as multiplier (e.g., 1.25 for 25% markup)</span>
                <span v-else-if="editForm.rate_type === 'percentage'">Enter as decimal (e.g., 0.03 for 3%)</span>
                <span v-else>Enter fixed amount in ₹</span>
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Effective From</label>
              <input
                v-model="editForm.effective_from"
                type="date"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
            </div>

            <div class="flex items-center">
              <input
                v-model="editForm.is_active"
                type="checkbox"
                :true-value="1"
                :false-value="0"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label class="ml-2 block text-sm text-gray-900">
                Active
              </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
              <button
                type="button"
                @click="editingRate = null"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="processing"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
              >
                {{ processing ? 'Saving...' : 'Save Rate' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from '@/Components/DataTable.vue';

const props = defineProps({
  rates: Object,
  filters: Object,
});

const columns = [
  { key: 'name', label: 'Rate Name' },
  { key: 'code', label: 'Code' },
  { key: 'rate_type', label: 'Type' },
  { key: 'rate_value', label: 'Value' },
  { key: 'effective_from', label: 'Effective From' },
  { key: 'is_active', label: 'Status' },
  { key: 'actions', label: 'Actions', sortable: false },
];

const editingRate = ref(null);
const processing = ref(false);
const editForm = ref({});

const searchFilters = ref({
  search: props.filters?.search || '',
  rate_type: props.filters?.rate_type || '',
  is_active: props.filters?.is_active || '',
});

const formatDate = (date) => {
  if (!date) return '-';
  return new Date(date).toLocaleDateString();
};

const filterRates = () => {
  router.get(route('rates.index'), searchFilters.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const editRate = (rate) => {
  editingRate.value = rate;
  editForm.value = {
    name: rate.name,
    code: rate.code,
    rate_value: rate.rate_value,
    rate_type: rate.rate_type,
    effective_from: rate.effective_from,
    is_active: rate.is_active,
  };
};

const saveRate = () => {
  processing.value = true;

  router.put(route('rates.update', editingRate.value.id), editForm.value, {
    onSuccess: () => {
      editingRate.value = null;
      processing.value = false;
    },
    onError: () => {
      processing.value = false;
    },
  });
};
</script>
