<template>
  <div class="app">
    <Header />
    <router-view />
  </div>
  <Home />
</template>

<script setup>
import Header from '@/components/Header.vue'
</script>

<style scoped></style>
