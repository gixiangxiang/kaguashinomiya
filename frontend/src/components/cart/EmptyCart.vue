<template>
  <div class="empty-cart">
    <i class="bx bx-cart-alt"></i>
    <p>您的購物車目前為空</p>
    <button @click="goToHome" class="shop-now-btn">立即選購</button>
  </div>
</template>

<script setup>
const emit = defineEmits(['goToHome'])
const goToHome = () => {
  emit('shop-now')
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/mixins.scss';

.empty-cart {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 0;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 8px 1px #00000007;

  i {
    color: #a0aec0;
    font-size: 5rem;
    margin-bottom: 20px;
  }

  p {
    color: #4a5568;
    font-size: 1.25rem;
    font-weight: 500;
    margin-bottom: 25px;
  }

  .shop-now-btn {
    @include primary-button;
  }
}
</style>
