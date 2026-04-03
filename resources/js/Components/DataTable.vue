<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              @click="column.sortable !== false ? sort(column.key) : null"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              :class="{ 'cursor-pointer hover:bg-gray-100': column.sortable !== false }"
            >
              <div class="flex items-center space-x-1">
                <span>{{ column.label }}</span>
                <span v-if="column.sortable !== false" class="flex flex-col">
                  <svg
                    class="w-3 h-3"
                    :class="sortKey === column.key && sortOrder === 'asc' ? 'text-indigo-600' : 'text-gray-400'"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" />
                  </svg>
                  <svg
                    class="w-3 h-3 -mt-1"
                    :class="sortKey === column.key && sortOrder === 'desc' ? 'text-indigo-600' : 'text-gray-400'"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" />
                  </svg>
                </span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="data.data && data.data.length === 0">
            <td :colspan="columns.length" class="px-6 py-4 text-center text-gray-500">
              {{ emptyMessage || 'No records found' }}
            </td>
          </tr>
          <slot name="body" :items="data.data || data" />
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="data.links && data.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      <div class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="changePage(data.current_page - 1)"
            :disabled="!data.prev_page_url"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button
            @click="changePage(data.current_page + 1)"
            :disabled="!data.next_page_url"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ data.from || 0 }}</span>
              to
              <span class="font-medium">{{ data.to || 0 }}</span>
              of
              <span class="font-medium">{{ data.total || 0 }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button
                v-for="(link, index) in data.links"
                :key="index"
                @click="link.url ? changePage(getPageFromUrl(link.url)) : null"
                :disabled="!link.url || link.active"
                v-html="link.label"
                :class="[
                  'relative inline-flex items-center px-3 py-2 border text-sm font-medium',
                  link.active
                    ? 'z-10 bg-indigo-600 border-indigo-600 text-white'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  !link.url ? 'cursor-not-allowed opacity-50' : 'cursor-pointer',
                  index === 0 ? 'rounded-l-md' : '',
                  index === data.links.length - 1 ? 'rounded-r-md' : ''
                ]"
              />
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  columns: {
    type: Array,
    required: true,
  },
  data: {
    type: Object,
    required: true,
  },
  emptyMessage: String,
  routeName: {
    type: String,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const sortKey = ref('');
const sortOrder = ref('asc');

const sort = (key) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortOrder.value = 'asc';
  }

  router.get(
    route(props.routeName),
    {
      ...props.filters,
      sort: sortKey.value,
      order: sortOrder.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const changePage = (page) => {
  if (!page) return;

  router.get(
    route(props.routeName),
    {
      ...props.filters,
      sort: sortKey.value,
      order: sortOrder.value,
      page: page,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const getPageFromUrl = (url) => {
  if (!url) return null;
  const urlParams = new URLSearchParams(url.split('?')[1]);
  return urlParams.get('page');
};

// Watch for URL changes to update sort state
watch(
  () => route().params,
  (params) => {
    if (params.sort) {
      sortKey.value = params.sort;
      sortOrder.value = params.order || 'asc';
    }
  },
  { immediate: true }
);
</script>
