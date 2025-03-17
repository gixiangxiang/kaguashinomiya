<template>
  <main class="product-display">
    <!-- 左側商品圖片區 -->
    <section class="product-image">
      <img :src="currentImage" :alt="product.name" class="main-image" />
      <div class="thumbnail-gallery">
        <img
          v-for="image in images"
          :key="image.id"
          :src="image.src"
          :alt="image.alt"
          @click="setCurrentImage(image.src)"
        />
      </div>
    </section>

    <!-- 右側商品資訊區 -->
    <section class="product-info">
      <!-- 商品名稱 -->
      <h2 class="product-name">{{ product.name }}</h2>

      <!-- 價格區域 -->
      <div class="product-price">
        <span class="price">NT$ {{ product.price.toLocaleString() }}</span>
      </div>

      <!-- 規格選擇區 -->
      <div class="product-options">
        <div class="option-group">
          <h3>尺寸</h3>
          <div class="option-buttons">
            <button
              v-for="(sizeOption, index) in product.size"
              :key="index"
              class="option-btn"
              @click="selectSize(sizeOption)"
              :class="{ active: selectedSize === sizeOption }"
            >
              {{ sizeOption }}
            </button>
          </div>
        </div>

        <div class="option-group">
          <h3>顏色</h3>
          <div class="option-buttons">
            <button
              v-for="(colorOption, index) in product.color"
              :style="{ backgroundColor: colorOption }"
              :key="index"
              :class="{ active: selectedColor === colorOption }"
              @click="selectColor(colorOption)"
              class="color-btn"
            ></button>
          </div>
        </div>
      </div>

      <!-- 產品簡介 -->
      <div class="product-description">
        <h3>商品介紹</h3>
        <p>{{ product.description }}</p>
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

// 聲明要接收的 props
const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
  images: {
    type: Array,
    default: () => [],
  },
})

const currentImage = ref(``) // 當前顯示的主圖
const selectedSize = ref(``) // 選中的尺寸
const selectedColor = ref(``) // 選中的顏色

// 初始化-預設顯示主圖
const initMainImage = () => {
  const mainImage = props.images.find((img) => img.isMain)
  if (mainImage) {
    currentImage.value = mainImage.src
  }
}

// 設置選擇顯示的圖片
const setCurrentImage = (imageSrc) => {
  currentImage.value = imageSrc
}

// 選擇尺寸
const selectSize = (size) => {
  selectedSize.value = size
}

// 選擇顏色
const selectColor = (color) => {
  selectedColor.value = color
}

// 初始化時設置主圖
onMounted(() => {
  initMainImage()
})

// 當產品或圖片變更時重置主圖
watch(
  () => [props.product, props.images],
  () => {
    initMainImage()
  }
)
</script>

<style lang="scss" scoped>
.product-display {
  display: flex;
  max-width: 1200px;
  margin: 100px auto 40px; // 上方留出 Header 的空間
  padding: 0 20px;
  gap: 40px;

  @media (max-width: 768px) {
    flex-direction: column;
    margin-top: 80px;
  }
}

// 左側圖片區
.product-image {
  flex: 1;

  img {
    width: 100%;
    border-radius: 8px;
    object-fit: cover;
  }

  .thumbnail-gallery {
    display: flex;
    gap: 10px;
    margin-top: 10px;

    img {
      width: 80px;
      height: 80px;
      border-radius: 4px;
      object-fit: cover;
      cursor: pointer;
      transition: all 0.2s;

      &:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px #0000001a;
      }
    }
  }
}

// 右側商品資訊區
.product-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

// 商品名稱
.product-name {
  font-size: 2rem;
  font-weight: 700;
  color: #2d3748;
  margin: 0;
}

// 價格區域
.product-price {
  display: flex;
  align-items: center;
  gap: 12px;

  .price {
    font-size: 1.75rem;
    font-weight: 700;
    color: #eb7d7d;
  }

  .original-price {
    font-size: 1rem;
    color: #718096;
    text-decoration: line-through;
  }

  .discount-tag {
    background-color: #e53e3e;
    color: #fff;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
  }
}

// 規格選擇區
.product-options {
  display: flex;
  flex-direction: column;
  gap: 20px;

  .option-group {
    h3 {
      font-size: 1rem;
      font-weight: 600;
      margin: 0 0 8px 0;
      color: #4a5568;
    }
  }

  .option-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;

    .option-btn {
      padding: 8px 16px;
      border: 1px solid #e2e8f0;
      background: white;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.2s;

      &:hover {
        border-color: #8c6d62;
      }

      &.active {
        background-color: #8c6d62;
        color: #fff;
        border-color: #8c6d62;
      }
    }

    .color-btn {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: 2px solid transparent;
      cursor: pointer;
      transition: all 0.2s;

      &:hover {
        transform: scale(1.1);
      }

      &.active {
        border-color: #4a5568;
        box-shadow:
          0 0 0 2px #fff,
          0 0 0 4px #8c6d62;
      }
    }
  }
}

// 產品簡介
.product-description {
  margin-top: 12px;

  h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 12px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
  }

  p {
    font-size: 1rem;
    line-height: 1.6;
    color: #4a5568;
    margin-bottom: 16px;
  }

  ul {
    padding-left: 20px;

    li {
      margin-bottom: 8px;
      color: #4a5568;
    }
  }
}
</style>
