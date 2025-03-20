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

import { ref, computed } from 'vue'

const products = ref([
  {
    id: 1,
    name: '天才的戀愛戰爭T-shirt 四宮輝夜',
    price: 1100,
    description: `戀愛，如同戰爭，只有率先讓對方告白的人，才能獲得勝利。這款 T-Shirt 以四宮輝夜為靈感，完美呈現她的優雅與聰慧，讓你穿上後彷彿置身於秀知院學園的戀愛戰場。`,
    size: ['S', 'M', 'L', 'XL'],
    colors: ['#FFFFFF', '#d4a9a3', '#8c6d62', '#2d4356'],
    images: [
      {
        id: 101,
        // productsId: 1, // 關聯到 products 的 id
        src: new URL('@/assets/product-img/T-Short-kaguya-0.jpeg', import.meta.url).href,
        alt: 'Kaguya T-Shirt - 主圖',
        isMain: true,
      },
      {
        id: 102,
        // productsId: 1, // 關聯到 products 的 id
        src: new URL('@/assets/product-img/T-Short-kaguya-1.jpeg', import.meta.url).href,
        alt: 'Kaguya T-Shirt - 副圖',
        isMain: false,
      },
    ],
  },
  {
    id: 2,
    name: '86不存在的戰區T-shirt 蕾娜',
    price: 780,
    description: `「即使相隔千里，我仍會聽見你的聲音。」——指揮官蕾娜，以她無可撼動的信念，成為 86 小隊的燈塔。這款 T-Shirt 以蕾娜為核心設計，象徵她的堅韌與溫柔，讓你穿上後，彷彿也能跨越戰火，與同伴並肩作戰。`,
    size: ['S', 'M', 'L', 'XL', 'XXL'],
    colors: ['#000000', '#494E38'],
    images: [
      {
        id: 201,
        // productsId: 2, // 關聯到 products 的 id
        src: new URL('@/assets/product-img/T-Short-86-0.jpeg', import.meta.url).href,
        alt: 'Reina T-Shirt - 主圖',
        isMain: true,
      },
      {
        id: 202,
        // productsId: 2, // 關聯到 products 的 id
        src: new URL('@/assets/product-img/T-Short-86-1.jpeg', import.meta.url).href,
        alt: 'Reina T-Shirt - 副圖',
        isMain: false,
      },
    ],
  },
])

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
