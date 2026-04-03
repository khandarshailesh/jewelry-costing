<template>
  <div class="relative" ref="selectRef">
    <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>

    <div class="relative">
      <input
        v-model="searchQuery"
        @focus="isOpen = true"
        @input="filterOptions"
        type="text"
        :placeholder="placeholder"
        :required="required"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10"
        :class="{ 'border-red-500': error }"
      />

      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </div>
    </div>

    <!-- Dropdown Options -->
    <div
      v-if="isOpen && filteredOptions.length > 0"
      class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
    >
      <div
        v-for="option in filteredOptions"
        :key="option[valueKey]"
        @click="selectOption(option)"
        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50"
        :class="{ 'bg-indigo-100': isSelected(option) }"
      >
        <span class="block truncate" :class="{ 'font-semibold': isSelected(option) }">
          {{ option[labelKey] }}
        </span>
        <span v-if="isSelected(option)" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </span>
      </div>
    </div>

    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  modelValue: [String, Number],
  options: {
    type: Array,
    required: true,
  },
  label: String,
  placeholder: {
    type: String,
    default: 'Select an option',
  },
  labelKey: {
    type: String,
    default: 'name',
  },
  valueKey: {
    type: String,
    default: 'id',
  },
  required: {
    type: Boolean,
    default: false,
  },
  error: String,
});

const emit = defineEmits(['update:modelValue']);

const selectRef = ref(null);
const searchQuery = ref('');
const isOpen = ref(false);
const filteredOptions = ref([...props.options]);

// Set initial search query from selected value
onMounted(() => {
  const selectedOption = props.options.find(opt => opt[props.valueKey] === props.modelValue);
  if (selectedOption) {
    searchQuery.value = selectedOption[props.labelKey];
  }
});

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
  const selectedOption = props.options.find(opt => opt[props.valueKey] === newValue);
  if (selectedOption) {
    searchQuery.value = selectedOption[props.labelKey];
  } else {
    searchQuery.value = '';
  }
});

// Watch for changes to options
watch(() => props.options, (newOptions) => {
  filteredOptions.value = [...newOptions];
  const selectedOption = newOptions.find(opt => opt[props.valueKey] === props.modelValue);
  if (selectedOption) {
    searchQuery.value = selectedOption[props.labelKey];
  }
});

const filterOptions = () => {
  const query = searchQuery.value.toLowerCase();
  filteredOptions.value = props.options.filter(option =>
    option[props.labelKey].toLowerCase().includes(query)
  );
};

const selectOption = (option) => {
  searchQuery.value = option[props.labelKey];
  emit('update:modelValue', option[props.valueKey]);
  isOpen.value = false;
};

const isSelected = (option) => {
  return option[props.valueKey] === props.modelValue;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (selectRef.value && !selectRef.value.contains(event.target)) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
