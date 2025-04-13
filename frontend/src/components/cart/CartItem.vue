<template>
  <div class="cart-item">
    <!-- 資訊 -->
    <div class="product-info">
      <img :src="`http://${product.imageUrl}`" :alt="product.name" />
      <div class="product-details">
        <h3>{{ product.name }}</h3>
        <div class="product-options">
          <div class="option">
            <span class="option-label">尺寸:</span>
            <span class="option-value size">{{ product.options.size }}</span>
          </div>

          <div class="option">
            <span class="option-label">顏色:</span>
            <span class="color-dot" :style="`background-color: ${product.options.color}`"></span>
          </div>
        </div>
      </div>
    </div>

    <!-- 單價 -->
    <div class="product-price">NT${{ millimeters(product.price) }}</div>

    <!-- 數量 -->
    <QuantitySelector :min="1" :max="99" v-model="product.quantity" />

    <!-- 小計 -->
    <div class="product-total">NT$ {{ millimeters(totalPrice) }}</div>

    <!-- 操作 -->
    <div class="product-action">
      <button class="remove-btn" @click="remove">
        <i class="bx bx-trash"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import QuantitySelector from '@/components/QuantitySelector.vue'
import { useMillimeters } from '@/composable/useMillimeters.js'
const { millimeters } = useMillimeters()
import { computed } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits(['remove'])

const totalPrice = computed(() => {
  return props.product.price * props.product.quantity
})

const remove = () => {
  emit('remove-product', props.product.id)
}
</script>

<style lang="scss" scoped>
.cart-item {
  display: grid;
  grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
  gap: 10px;
  align-content: center;
  padding: 15px 20px;
  border-bottom: 1px solid #e2e8f0;

  .product-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;

    img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 4px;
    }

    .product-details {
      h3 {
        color: #2d3748;
        margin: 0 0 8px 0;
        font-weight: 500;
        font-size: 1rem;
      }

      .product-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;

        .option {
          display: flex;
          align-items: center;
          gap: 5px;
          font-size: 0.875rem;
          color: #718096;

          .option-label {
            font-weight: 500;
          }

          .option-value {
            color: #4a5568;

            &.size {
              background-color: #f7fafc;
              padding: 2px 8px;
              border-radius: 3px;
              border: 1px solid #e2e8f0;
            }
          }

          .color-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1px solid #e2e8f0;
          }
        }
      }
    }
  }

  .product-price,
  .product-total {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    color: #2d3748;
  }

  .product-action {
    display: flex;
    align-items: center;
    justify-content: center;

    .remove-btn {
      cursor: pointer;
      background-color: transparent;
      border: none;
      color: #a0aec0;
      font-size: 1.25rem;
      transition: all 0.2s ease;

      &:hover {
        color: #e53e3e;
        transform: scale(1.1);
      }
    }
  }
}
</style>
