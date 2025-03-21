<template>
  <Header />
  <ProductDisplay v-if="selectedProduct" :product="selectedProduct" />
  <ProductList :products="products" @select-product="changeSelectedProduct" />
  <!-- 新增產品彈跳視窗 -->
</template>

<script setup>
import Header from '@/components/Header.vue'
import ProductDisplay from '@/components/ProductDisplay.vue'
import ProductList from '@/components/ProductList.vue'
import axios from 'axios'

import { ref, computed, onMounted } from 'vue'

const products = ref([]) // 存儲 API 獲取產品數據
const error = ref(null)

onMounted(async () => {
  try {
    const response = await axios.get(`/api/products-json/all`)
    const data = response.data.products
    console.log(data)
    console.log(Array.isArray(data.images))
    products.value = data
  } catch (err) {
    error.value = err.repsonse.data
  }
})

// 默認選中的產品 ID
const selectedProductId = ref(1)

// 計算屬性：當前選中的產品 (用於傳入產品展示區)
const selectedProduct = computed(() => {
  return products.value.find((product) => product.id === selectedProductId.value)
})

// 切換選中的產品傳入產品展示區
const changeSelectedProduct = (productId) => {
  selectedProductId.value = productId
}
</script>

<style scoped></style>
