<template>
  <div class="product-quantity">
    <button class="quntity-btn" @click="decrease" :disabled="quantity <= min">-</button>
    <span class="quantity">{{ quantity }}</span>
    <button class="quntity-btn" @click="increase" :disabled="quantity >= max">+</button>
  </div>
</template>

<script setup>
const props = defineProps({
  max: {
    type: Number,
    default: 99,
  },
  min: {
    type: Number,
    default: 1,
  },
})

const quantity = defineModel()

const decrease = () => {
  if (quantity.value > props.min) {
    quantity.value--
  }
}

const increase = () => {
  if (quantity.value < props.max) {
    quantity.value++
  }
}
</script>

<style lang="scss" scoped>
.product-quantity {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;

  .quntity-btn {
    cursor: pointer;
    width: 30px;
    height: 30px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    background-color: #fff;
    transition: all 0.2s ease;

    &:hover {
      background-color: #f8f9fa;
    }
  }
}
</style>
