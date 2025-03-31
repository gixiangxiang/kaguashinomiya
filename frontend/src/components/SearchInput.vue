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
  emit('search', searchQuery.value)
}

const debounceSearch = debounce(() => {
  handleSearch()
}, 300)
</script>

<style lang="scss" scoped>
.product-search {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap; // 允許在需要時換行

  label {
    white-space: nowrap; // 防止標籤文字斷行
    margin-right: 5px;
  }
}

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

@media (max-width: 576px) {
  .product-search {
    // flex-direction: column; // 垂直排列
    align-items: flex-start;
    width: 100%;

    label {
      margin-bottom: 4px;
    }

    .search-input {
      width: 100%;
      max-width: 100%; // 在手機上佔滿容器寬度
    }
  }
}
</style>
