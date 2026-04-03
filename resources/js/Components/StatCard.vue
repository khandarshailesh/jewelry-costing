<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center">
      <span class="text-2xl mr-3">{{ icon }}</span>
      <div>
        <p class="text-sm text-gray-500">{{ title }}</p>
        <p class="text-2xl font-bold">{{ value }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [Number, String],
  icon: String,
});
</script>
