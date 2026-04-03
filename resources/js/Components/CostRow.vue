<template>
  <div :class="['flex justify-between items-center py-1', highlight ? 'font-semibold bg-gray-100 px-2 rounded' : '']">
    <div>
      <span>{{ label }}</span>
      <span v-if="formula" class="text-xs text-gray-500 ml-2">({{ formula }})</span>
    </div>
    <span>₹{{ Number(value).toFixed(4) }}</span>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  formula: String,
  value: [Number, String],
  highlight: Boolean,
});
</script>
