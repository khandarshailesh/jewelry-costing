<template>
  <AppLayout title="Costing Calculator">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Costing Calculator
      </h2>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Input Form -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium mb-4">Product Details</h3>

        <form @submit.prevent="calculate" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Weight (RASS) *
              </label>
              <input
                v-model.number="form.weight"
                type="number"
                step="0.0001"
                class="input-field"
                placeholder="e.g., 2.111"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Stone Type
              </label>
              <select v-model="form.stone_type" class="input-field">
                <option v-for="stoneType in stoneTypes" :key="stoneType.id" :value="stoneType.code">
                  {{ stoneType.name }} (₹{{ parseFloat(stoneType.price_per_unit).toFixed(4) }})
                </option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                MINO Count
              </label>
              <input
                v-model.number="form.mino_count"
                type="number"
                class="input-field"
                placeholder="0"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Stone Count
              </label>
              <input
                v-model.number="form.stone_count"
                type="number"
                class="input-field"
                placeholder="0"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Chips Count
              </label>
              <input
                v-model.number="form.chips_count"
                type="number"
                class="input-field"
                placeholder="0"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                OXO Factor
              </label>
              <select v-model.number="form.oxo_factor" class="input-field">
                <option v-for="oxoFactor in oxoFactors" :key="oxoFactor.id" :value="oxoFactor.value">
                  {{ oxoFactor.value }} - {{ oxoFactor.name }}
                </option>
              </select>
            </div>
          </div>

          <button type="submit" class="w-full btn-primary" :disabled="loading">
            {{ loading ? 'Calculating...' : 'Calculate Cost' }}
          </button>
        </form>

        <!-- Current Rates Info -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
          <h4 class="font-medium mb-2">Current Rates</h4>
          <div class="grid grid-cols-2 gap-2 text-sm">
            <div>Metal Rate: <span class="font-semibold">{{ rates.metal_rate }}×</span></div>
            <div>MINO Rate: <span class="font-semibold">₹{{ rates.mino_rate }}</span></div>
            <div>Wastage: <span class="font-semibold">{{ (rates.wastage_percentage * 100).toFixed(1) }}%</span></div>
            <div>Markup 1: <span class="font-semibold">{{ rates.markup_1 }}×</span></div>
            <div>Markup 2: <span class="font-semibold">{{ rates.markup_2 }}×</span></div>
          </div>
        </div>
      </div>

      <!-- Results -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium mb-4">Cost Breakdown</h3>

        <div v-if="result" class="space-y-3">
          <CostRow label="Metal Cost" :formula="`${form.weight} × ${rates.metal_rate}`" :value="result.metal_cost" />
          <CostRow label="Stone Cost" :formula="`${currentStoneRate} × ${form.stone_count}`" :value="result.stone_cost" />
          <CostRow label="OXO Cost" :formula="`(${form.weight} × ${form.oxo_factor}) / 10`" :value="result.oxo_cost" />
          <CostRow label="MINO Cost" :formula="`${rates.mino_rate} × ${form.mino_count}`" :value="result.mino_cost" />
          <CostRow label="Chips Cost" :value="result.chips_cost" />

          <div class="border-t pt-3">
            <CostRow label="Gross Total" :value="result.gross_total" highlight />
          </div>

          <CostRow label="Wastage (Ghasaro)" :formula="`${(rates.wastage_percentage * 100)}%`" :value="result.wastage_amount" />
          <CostRow label="Total with Wastage" :value="result.total_with_wastage" />
          <CostRow label="After Markup 1" :formula="`× ${rates.markup_1}`" :value="result.price_after_markup1" />

          <div class="border-t pt-3">
            <div class="flex justify-between items-center p-3 bg-green-100 rounded-lg">
              <span class="font-bold text-lg">Final Price</span>
              <span class="font-bold text-2xl text-green-700">₹{{ Number(result.final_price).toFixed(4) }}</span>
            </div>
          </div>
        </div>

        <div v-else class="text-center text-gray-500 py-12">
          <p>Enter product details and click Calculate to see the cost breakdown.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CostRow from '@/Components/CostRow.vue';

const props = defineProps({
  rates: Object,
  stoneTypes: Array,
  oxoFactors: Array,
});

const form = ref({
  weight: null,
  mino_count: 0,
  stone_count: 0,
  chips_count: 0,
  oxo_factor: 0,
  stone_type: 'k',
});

const result = ref(null);
const loading = ref(false);

const currentStoneRate = computed(() => {
  const type = form.value.stone_type;
  if (type === 'S') return props.rates.stone_rate_high;
  if (type === 'G' || type === 'A') return props.rates.stone_rate_low;
  return props.rates.stone_rate_std;
});

const calculate = async () => {
  if (!form.value.weight) return;

  loading.value = true;

  try {
    const response = await fetch('/costing/quick-calculate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      body: JSON.stringify(form.value),
    });

    result.value = await response.json();
  } catch (error) {
    console.error('Calculation error:', error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.input-field {
  @apply w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500;
}
.btn-primary {
  @apply bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition disabled:opacity-50;
}
</style>
