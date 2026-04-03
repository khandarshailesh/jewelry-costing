<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <Link href="/dashboard" class="text-xl font-bold text-indigo-600">
                Jewelry Costing
              </Link>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <NavLink href="/dashboard" :active="route().current('dashboard')">
                Dashboard
              </NavLink>
              <NavLink href="/products" :active="route().current('products.*')">
                Products
              </NavLink>
              <NavLink href="/costing/calculator" :active="route().current('costing.*')">
                Costing
              </NavLink>
              <NavLink href="/materials" :active="route().current('materials.*')">
                Materials
              </NavLink>
              <NavLink href="/rates" :active="route().current('rates.*')">
                Rates
              </NavLink>

              <!-- Masters Dropdown -->
              <div class="relative inline-flex items-center">
                <button
                  @click="showMastersMenu = !showMastersMenu"
                  type="button"
                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-150"
                  :class="route().current('categories.*') || route().current('stone-types.*') || route().current('oxo-factors.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                >
                  Masters
                  <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>

                <div
                  v-if="showMastersMenu"
                  @click="showMastersMenu = false"
                  class="absolute top-full left-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50"
                >
                  <Link
                    :href="route('categories.index')"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Categories
                  </Link>
                  <Link
                    :href="route('stone-types.index')"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    Stone Types
                  </Link>
                  <Link
                    :href="route('oxo-factors.index')"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  >
                    OXO Factors
                  </Link>
                </div>
              </div>

              <NavLink href="/users" :active="route().current('users.*')">
                Users
              </NavLink>
            </div>
          </div>

          <!-- User Menu -->
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <div class="relative">
              <button
                @click="showUserMenu = !showUserMenu"
                type="button"
                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700"
              >
                <span>{{ $page.props.auth?.user?.name || 'User' }}</span>
                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>

              <div
                v-if="showUserMenu"
                @click="showUserMenu = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50"
              >
                <Link
                  :href="route('profile.edit')"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Profile
                </Link>
                <Link
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Log Out
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <header v-if="$slots.header" class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- Flash Messages -->
    <div v-if="$page.props.flash?.success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ $page.props.flash.success }}
      </div>
    </div>

    <!-- Main Content -->
    <main>
      <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
          <slot />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';

const showUserMenu = ref(false);
const showMastersMenu = ref(false);
</script>
