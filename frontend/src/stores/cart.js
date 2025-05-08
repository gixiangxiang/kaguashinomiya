import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore = defineStore(
  'cart',
  () => {
    // State
    const items = ref([])

    // Getters
    const totalItems = computed(() => {
      return items.value.reduce((sum, item) => sum + item.quantity, 0)
    })

    const totalPrice = computed(() => {
      return items.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
    })

    // Actions
    const addToCart = (product, quantity, selectedSize, selectedColor) => {
      // 檢查現有商品是否已存在於購物車中
      const existingItemIndex = items.value.findIndex(
        (item) =>
          item.id === product.id &&
          item.selectedSize === selectedSize &&
          item.selectedColor === selectedColor
      )

      if (existingItemIndex !== -1) {
        // 如果商品已存在 則增加用戶選擇數量
        items.value[existingItemIndex].quantity += quantity
      } else {
        // 如果商品不存在 則將其添加到購物車中
        items.value.push({
          id: product.id,
          name: product.name,
          price: product.price,
          image: product.image,
          quantity,
          selectedSize,
          selectedColor,
        })
      }
    }

    // 移除購物車中的商品
    const removeFromCart = (id, selectedSize, selectedColor) => {
      const index = items.value.findIndex(
        (item) =>
          item.id === id &&
          item.selectedSize === selectedSize &&
          item.selectedColor === selectedColor
      )

      if (index !== -1) {
        items.value.splice(index, 1)
      }
    }

    // 增加或減少購物車中的商品數量
    const updateQuantity = (id, newQuantity, selectedSize, selectedColor) => {
      const item = items.value.find(
        (item) =>
          item.id === id &&
          item.selectedSize === selectedSize &&
          item.selectedColor === selectedColor
      )

      if (item && newQuantity > 0) {
        item.quantity = newQuantity
      }
    }

    return {
      // State
      items,
      // Getters
      totalItems,
      totalPrice,
      // Actions
      addToCart,
      removeFromCart,
      updateQuantity,
    }
  },
  {
    persist: {
      key: 'cart',
      storage: localStorage,
      paths: ['items'],
    },
  }
)
