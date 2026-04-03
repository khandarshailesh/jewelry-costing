<template>
  <AppLayout title="Dashboard">
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <StatCard
          title="Total Products"
          :value="stats.total_products"
          icon="📦"
          color="blue"
        />
        <StatCard
          title="Active Products"
          :value="stats.active_products"
          icon="✅"
          color="green"
        />
        <StatCard
          title="Total Materials"
          :value="stats.total_materials"
          icon="🔧"
          color="purple"
        />
        <StatCard
          title="Costings Today"
          :value="stats.costings_today"
          icon="📊"
          color="yellow"
        />
        <StatCard
          title="This Month"
          :value="stats.costings_this_month"
          icon="📈"
          color="indigo"
        />
      </div>

      <!-- Quick Actions -->
      <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <Link
            :href="route('costing.calculator')"
            class="flex flex-col items-center justify-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition"
          >
            <span class="text-2xl mb-2">🧮</span>
            <span class="text-sm font-medium text-gray-900">Calculate Cost</span>
          </Link>
          <Link
            :href="route('products.create')"
            class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition"
          >
            <span class="text-2xl mb-2">➕</span>
            <span class="text-sm font-medium text-gray-900">Add Product</span>
          </Link>
          <Link
            :href="route('materials.create')"
            class="flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition"
          >
            <span class="text-2xl mb-2">🔨</span>
            <span class="text-sm font-medium text-gray-900">Add Material</span>
          </Link>
          <Link
            :href="route('costing.history')"
            class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition"
          >
            <span class="text-2xl mb-2">📜</span>
            <span class="text-sm font-medium text-gray-900">View History</span>
          </Link>
        </div>
      </div>

      <!-- Recent Costings -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Recent Costing History</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Price</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="recent_costings.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                  No costing history yet
                </td>
              </tr>
              <tr v-for="costing in recent_costings" :key="costing.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(costing.created_at) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ costing.product?.product_number || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ costing.weight }} g
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ₹{{ parseFloat(costing.gross_total || 0).toFixed(4) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  ₹{{ parseFloat(costing.final_price || 0).toFixed(4) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/StatCard.vue';

const props = defineProps({
  stats: Object,
  recent_costings: Array,
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};
</script>
