<template>
  <div class="cart-summary">
    <div class="summary-row">
      <span>商品總數：</span>
      <span>{{ cartStore.totalItems }} 件</span>
    </div>
    <div class="summary-row">
      <span>總金額：</span>
      <span class="total-price">NT$ {{ millimeters(cartStore.totalPrice) }}</span>
    </div>
    <button class="checkout-btn" @click="showToBeContinued">
      前往結帳<i class="bx bx-right-arrow-alt"></i>
    </button>
  </div>
</template>

<script setup>
import { useMillimeters } from '@/composable/useMillimeters.js'
import { useCartStore } from '@/stores/cart.js'

const cartStore = useCartStore()
const { millimeters } = useMillimeters()

const emit = defineEmits(['showToBeContinued'])

const showToBeContinued = () => {
  emit('showToBeContinued')
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/mixins.scss';

.cart-summary {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: center;
  padding: 15px 20px;
  background-color: #f8f9fa;
  border-radius: 0 0 8px 8px;

  .summary-row {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 15px;

    span {
      font-size: 1rem;
    }

    .total-price {
      color: #2d3748;
      font-size: 1.25rem;
      font-weight: 600;
    }
  }

  .checkout-btn {
    @include primary-button;

    i {
      margin-left: 5px;
    }
  }
}
</style>
