<template>
  <Header />
  <ProductDisplay v-if="selectedProduct" :product="selectedProduct" />
  <ProductList
    :products="products"
    @select-product="changeSelectedProduct"
    @search="handleSearch"
    @edit-product="handleEditProduct"
  />
  <ProductEditor
    @submit-product="handleAddProduct"
    @cancel-edit="cancelEdit"
    @update-product="handleUpdateProduct"
    :isLoading="isLoading"
    :lastSubmitResult="lastSubmitResult"
    :editMode="editMode"
    :productToEdit="productToEdit"
  />
</template>

<script setup>
import Header from '@/components/Header.vue'
import ProductDisplay from '@/components/specific/ProductDisplay.vue'
import ProductList from '@/components/specific/ProductList.vue'
import ProductEditor from '../components/specific/ProductEditor.vue'
import { productApi } from '../server/api/productApi'

import { ref, computed, onMounted } from 'vue'

const products = ref([]) // 存儲 API 獲取產品數據
const isLoading = ref(false)
const lastSubmitResult = ref(null)
const selectedProductId = ref(null) // 默認選中的產品 ID
const editMode = ref(false) // 編輯模式開關
const productToEdit = ref(null) // 編輯的產品數據

const handleEditProduct = (product) => {
  editMode.value = true
  productToEdit.value = product
}

const cancelEdit = () => {
  editMode.value = false
  productToEdit.value = null
}

const fetchAllProducts = async () => {
  try {
    const data = await productApi.getAllProducts()
    products.value = data
    selectedProductId.value = data[0]?.id
  } catch (err) {
    console.error(`獲取產品失敗:${err}`)
  }
}

const handleSearch = async (keyword) => {
  // 如果搜尋為空，顯示所有產品
  if (keyword === '') {
    await fetchAllProducts()
    return
  }

  try {
    isLoading.value = true
    const data = await productApi.searchProducts(keyword)
    products.value = data

    if (data && data.length > 0) {
      selectedProductId.value = data[0].id // 如果有搜尋結果，選中第一個產品
    }
  } catch (err) {
    console.error(`搜尋產品失敗:${err}`)
  } finally {
    isLoading.value = false
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

const handleUpdateProduct = async (updatedProduct) => {
  isLoading.value = true
  lastSubmitResult.value = null
  try {
    await productApi.updateProduct(updatedProduct)
    await fetchAllProducts()

    lastSubmitResult.value = { success: true }
  } catch (err) {
    lastSubmitResult.value = { success: false }
    console.error(`編輯產品失敗：${err}`)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchAllProducts)

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
