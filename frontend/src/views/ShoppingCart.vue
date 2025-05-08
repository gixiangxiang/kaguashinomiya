<template>
  <ToastMessage :toast="toast" />
  <main class="shopping-cart">
    <div class="cart-header">
      <h1>我的購物車</h1>
      <button @click="goToHome" class="continue-shopping">
        <i class="bx bx-arrow-back"></i>
        繼續購物
      </button>
    </div>

    <!-- 購物車為空的顯示 -->
    <EmptyCart v-if="!cartStore.items.length" @shop-now="goToHome" />

    <!-- 購物車產品列表 -->
    <div class="cart-container" v-else>
      <!-- 購物車項目表頭 -->
      <div class="cart-item-header">
        <span class="product-info">商品資訊</span>
        <span>單價</span>
        <span>數量</span>
        <span>小計</span>
        <span>操作</span>
      </div>

      <!-- 購物車列表 -->
      <div class="cart-items">
        <CartItem v-for="item in cartStore.items" :key="item.id" :product="item" />
      </div>

      <!-- 購物車摘要區域 -->
      <CartSummary @showToBeContinued="onShowToBeContinued" />
    </div>
  </main>
</template>

<script setup>
import ToastMessage from '@/components/ToastMessage.vue'
import EmptyCart from '@/components/cart/EmptyCart.vue'
import CartItem from '@/components/cart/CartItem.vue'
import CartSummary from '@/components/cart/CartSummary.vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart.js'
import { ref, computed } from 'vue'

const router = useRouter()
const cartStore = useCartStore()

const toast = ref({
  show: false,
  type: '',
  message: '',
})

const goToHome = () => {
  router.push({
    name: 'Home',
  })
}

const onShowToBeContinued = () => {
  toast.value = {
    show: true,
    type: 'success',
    message: 'To Be Continued...',
  }

  setTimeout(() => {
    toast.value.show = false
  }, 3000)
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/mixins.scss';

.shopping-cart {
  max-width: 1200px;
  margin: 100px auto 40px; // 上方留出 Header 的空間

  @media screen and (max-width: 768px) {
    margin: 80px auto 30px;
  }
}

.cart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;

  h1 {
    font-size: 1.45rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
  }

  .continue-shopping {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    background-color: transparent;
    border: none;
    color: #4a5568;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.2s ease-in-out;

    &:hover {
      color: #2d3748;
      transform: translateX(-3px);
    }
  }

  @media screen and (max-width: 768px) {
    flex-direction: column;
    align-items: flex-start;
    padding: 0 20px;
    gap: 15px;

    h1 {
      font-size: 1.3rem;
    }

    .continue-shopping {
      align-self: flex-start;
    }
  }
}

.cart-container {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 8px 1px #00000007;
}

.cart-item-header {
  display: grid;
  grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
  gap: 10px;
  background-color: #f8f9fa;
  padding: 15px 20px;
  color: #4a5568;
  font-weight: 600;
  border-radius: 8px 8px 0 0;
  border-bottom: 1px solid #e2e8f0;

  span {
    text-align: center;
  }

  .product-info {
    text-align: center;
  }

  @media screen and (max-width: 768px) {
    display: none; // 在手機上隱藏表頭，使用偽元素替代
  }
}
</style>
