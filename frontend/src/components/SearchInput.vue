<template>
  <div class="product-search">
    <label for="search-input">搜尋產品：</label>
    <input
      v-model.trim="searchQuery"
      @keyup.enter="debounceSearch"
      id="search-input"
      type="text"
      class="search-input"
      placeholder="搜尋商品..."
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useDebounce } from '../composable/useDebounce'

const emit = defineEmits(['search'])

const searchQuery = ref('')
const { debounce } = useDebounce()

const handleSearch = () => {
  if (searchQuery.value === '') return
  emit('search', searchQuery.value)
}

const debounceSearch = debounce(() => {
  handleSearch()
}, 300)
</script>

<style lang="scss" scoped>
.search-input {
  padding: 10px 16px;
  border: none;
  outline: none;
  border-radius: 5px;
  box-shadow: inset 0 0 5px 1px rgba(0, 0, 0, 0.2);
  color: #2e3748;
  font-size: 0.95rem;
  min-width: 200px;
  transition: box-shadow 0.3s ease;

  &:focus {
    box-shadow: inset 0 0 6px 1px rgba(46, 55, 72, 0.3);
  }

  &::placeholder {
    color: #aaa;
    opacity: 0.8;
  }
}
</style>
