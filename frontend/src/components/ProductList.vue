<template>
  <section class="product-list">
    <div class="product-header">
      <h2 class="product-title">更多潮流動漫服飾</h2>
      <div class="product-search">
        <label for="search-input">搜尋產品：</label>
        <input id="search-input" type="text" class="search-input" placeholder="搜尋商品..." />
      </div>
    </div>

    <div v-if="isLoading" class="loading">資料載入中...</div>
    <div v-else-if="products.length === 0" class="no-products">暫無商品</div>
    <ul class="product-grid">
      <li v-for="product in products" :key="product.id" class="product-card">
        <div @click="selectProduct(product.id)" class="image-wrapper">
          <img :src="getProductMainImage(product)" :alt="product.name" class="product-image" />
        </div>
        <h5 class="product-name">{{ product.name }}</h5>
        <p class="product-price">NT$ {{ product.price.toLocaleString() }}</p>
        <div class="product-actions">
          <button class="edit-btn">編輯</button>
          <button class="delete-btn">刪除</button>
        </div>
      </li>
    </ul>
  </section>
</template>

<script setup>
import { ref } from 'vue'

// 聲明接收的 props
const props = defineProps({
  products: {
    type: Array,
    required: true,
  },
  // images: {
  //   type: Array,
  //   default: () => [],
  // },
})

// 聲明向父組件發出的事件
const emit = defineEmits(['select-product'])

const isLoading = ref(false) // 是否載入中 (先預設為 false 等API測試再改為 true)

const getProductMainImage = (product) => {
  // 找出商品的主要圖片
  const mainImage = product.images.find((image) => image.isMain)
  return mainImage.src
}

// 點擊商品圖時觸發 select-product 事件
const selectProduct = (productId) => {
  emit('select-product', productId)
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  })
}
</script>

<style lang="scss" scoped>
.product-list {
  width: 100%;
  padding: 60px;
  border-top: 1px solid #ccc;

  .product-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 50px;
    flex-wrap: wrap;
    gap: 20px;
  }

  .product-title {
    position: relative;
    color: #2e3748;
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: 2.5px;
    padding-bottom: 10px;

    &::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 110px;
      height: 3px;
      background-color: #2e3748;
      border-radius: 2px;
      transition: width 0.3s ease;
    }

    &:hover::after {
      width: 100%;
    }
  }

  .product-search {
    display: flex;
    align-items: center;
    gap: 10px;

    label {
      font-size: 1.125rem;
      font-weight: 600;
      color: #2e3748;
      white-space: nowrap;
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
  .loading {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2e3748;
  }

  .no-products {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2e3748;
  }
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
}

.product-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #fff;
  border-radius: 12px;
  padding: 10px;
  box-shadow: 0 8px 20px #00000016;
  overflow: hidden;

  .product-name {
    width: 100%;
    font-size: 1rem;
    font-weight: 600;
    color: #2e3748;
    line-height: 1.5;
    margin: 12px 0 6px;
    text-align: center;
  }

  .product-price {
    width: 100%;
    font-size: 0.875rem;
    font-weight: 500;
    color: #aaa;
    line-height: 1.5;
    margin-bottom: 16px;
    text-align: center;
  }
}

.image-wrapper {
  cursor: pointer;
  width: 280px;
  height: 280px;
  overflow: hidden;
  border-radius: 8px;
  position: relative;

  &::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, #00000000 70%, #00000008 100%);
    z-index: 1;
  }

  .product-image {
    display: block;
    object-fit: contain;
    width: 100%;
    height: 100%;
  }
}

.product-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
  width: 100%;
  padding: 10px 0;
  opacity: 0.95;

  .delete-btn,
  .edit-btn {
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    max-width: 120px;
    flex: 1;
    background-color: #37474f;
  }
}

// 添加響應式設計
@media (max-width: 768px) {
  .product-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .product-search {
    width: 100%;
    margin-top: 20px;
  }

  .search-input {
    flex-grow: 1;
  }

  .product-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
  }

  .image-wrapper {
    height: 240px;
  }
}
</style>
