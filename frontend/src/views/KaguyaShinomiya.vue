<template>
  <Header />
  <ProductDisplay v-if="selectedProduct" :product="selectedProduct" />
  <ProductList
    :products="products"
    @select-product="changeSelectedProduct"
    @search="handleSearch"
  />
  <AddProduct
    @submit-product="handleAddProduct"
    :isLoading="isLoading"
    :lastSubmitResult="lastSubmitResult"
  />
</template>

<script setup>
import Header from '@/components/Header.vue'
import ProductDisplay from '@/components/specific/ProductDisplay.vue'
import ProductList from '@/components/specific/ProductList.vue'
import AddProduct from '../components/specific/AddProduct.vue'
import { productApi } from '../server/api/productApi'

import { ref, computed, onMounted } from 'vue'

const products = ref([]) // 存儲 API 獲取產品數據
const isLoading = ref(false)
const lastSubmitResult = ref(null)
const searchQuery = ref('') // 搜尋關鍵字

const fetchAllProducts = async () => {
  try {
    const data = await productApi.getAllProducts()
    products.value = data
  } catch (err) {
    console.error(`獲取產品失敗:${err}`)
  }
}

const handleAddProduct = async (newProduct) => {
  isLoading.value = true
  lastSubmitResult.value = null // 重置結果確保被 watch 偵測到

  try {
    await productApi.addProduct(newProduct)
    await fetchAllProducts()

    lastSubmitResult.value = { success: true }
  } catch (err) {
    lastSubmitResult.value = { success: false }
    console.error(`新增產品失敗：${err}`)
  } finally {
    isLoading.value = false
  }
}

const handleSearch = async (keyword) => {
  // 如果搜尋為空，顯示所有產品
  if (!keyword.trim()) {
    await fetchAllProducts()
    return
  }

  try {
    isLoading.value = true
    const data = await productApi.searchProducts(keyword)
    products.value = data
  } catch (err) {
    console.error(`搜尋產品失敗:${err}`)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchAllProducts)

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
