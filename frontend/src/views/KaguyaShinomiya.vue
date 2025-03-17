<template>
  <Header />
  <ProductDisplay
    v-if="selectedProduct"
    :product="selectedProduct"
    :images="getProductImages(selectedProduct.id)"
  />
  <!-- 產品卡片區 -->
  <!-- 新增產品彈跳視窗 -->
</template>

<script setup>
import Header from '../components/Header.vue'
import ProductDisplay from '../components/ProductDisplay.vue'

import { ref, computed } from 'vue'

const products = ref([
  {
    id: 1,
    name: '四宮輝夜短T',
    price: 1580,
    description: `這款精緻日式和服採用傳統工藝製作，由100%高品質絲綢材質打造。每一件都經過專業匠人手工縫製，細節處理精良，完美展現日本傳統美學。適合各種正式場合穿著，也是收藏和體驗日本文化的絕佳選擇。`,
    size: ['S', 'M', 'L', 'XL'],
    color: ['#FFFFFF', '#d4a9a3', '#8c6d62', '#2d4356'],
  },
])

const allProductImages = ref([
  {
    id: 101,
    productsId: 1, // 關聯到 products 的 id
    src: new URL('../assets/product-img/T-Short-kaguya-0.jpeg', import.meta.url).href,
    alt: 'Kaguya T-Shirt - 主圖',
    isMain: true,
  },
  {
    id: 102,
    productsId: 1, // 關聯到 products 的 id
    src: new URL('../assets/product-img/T-Short-kaguya-1.jpeg', import.meta.url).href,
    alt: 'Kaguya T-Shirt - 副圖',
    isMain: false,
  },
])

// 默認選中的產品 ID
const selectedProductId = ref(1)

// 計算屬性：當前選中的產品 (用於傳入產品展示區)
const selectedProduct = computed(() => {
  return products.value.find((product) => product.id === selectedProductId.value)
})

// 根據產品 ID 篩選出對應的產品圖片 (用於傳入產品展示區)
const getProductImages = (productId) => {
  return allProductImages.value.filter((image) => image.productsId === productId)
}

// 切換選中的產品傳入產品展示區
// const changeSelectedProduct = (productId) => {
// selectedProductId.value = productId
// }
</script>

<style scoped></style>
