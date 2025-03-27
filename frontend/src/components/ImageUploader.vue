<template>
  <label>{{ label }}</label>

  <div class="image-uploader">
    <div class="previews-container">
      <!-- 預覽圖片區域 -->
      <div v-for="(image, index) in images" :key="index" class="image-preview">
        <img :src="image.src" alt="預覽圖片" />
        <button type="button" class="delete-btn" @click="removeItem(index)">
          <i class="bx bx-x"></i>
        </button>

        <p class="main-label" v-if="image.isMain">main</p>
        <button
          v-else
          type="button"
          class="set-main-btn"
          title="設為主圖"
          @click="setMainImage(index)"
        >
          <i class="bx bx-star"></i>
        </button>
      </div>

      <!-- 上傳區域 -->
      <div class="upload-container">
        <input
          type="file"
          @change="handleFileChange"
          ref="fileInput"
          :disabled="isDisabled"
          accept="image/*"
          multiple
          required
        />
        <div
          class="upload-box"
          :class="{ disabled: isDisabled, dragging: isDragging }"
          @click="triggerFileInput"
          @dragover.prevent="isDragging = true"
          @dragenter.prevent="isDragging = true"
          @dragleave="isDragging = false"
          @drop.prevent="handleDrop"
        >
          <i class="bx bx-cloud-upload"></i>
          <span>{{ isDragging ? '放開以上傳' : '點擊或拖曳上傳' }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const emit = defineEmits(['show-toast'])

const fileInput = ref(null) // 用於獲取圖片輸入DOM
const isDragging = ref(false) // 拖曳狀態追蹤

const images = defineModel('images', {
  type: Array,
  default: () => [],
})

const props = defineProps({
  label: {
    type: String,
  },
  isDisabled: {
    type: Boolean,
    default: false,
  },
  maxImages: {
    type: Number,
    default: 5,
  },
  MAX_SIZE: {
    type: Number,
    default: 1 * 1024 * 1024,
  },
})

const triggerFileInput = () => {
  if (props.isDisabled) return
  fileInput.value.click()
}

const handleDrop = (event) => {
  if (props.isDisabled) return
  // event.preventDefault()
  isDragging.value = false

  const droppedFiles = event.dataTransfer.files
  if (!droppedFiles.length) return

  processFiles(droppedFiles)
}

const handleFileChange = (event) => {
  if (props.isDisabled) return

  const files = event.target.files
  if (!files.length) return

  processFiles(files)

  event.target.value = null // 重置 input 的值，讓同一張圖片可以重複上傳
}

const processFiles = (files) => {
  const newImages = [...images.value]

  const canAddCount = props.maxImages - newImages.length
  if (canAddCount <= 0) {
    emit('show-toast', {
      type: 'error',
      message: `最多只能上傳${props.maxImages}張圖片`,
    })
    return
  }

  // 限制上傳的圖片數量(FileList 是類陣列物件，不具有 slice 等陣列方法)
  const filesToProcess = Array.from(files).slice(0, props.maxImages - newImages.length)
  let loadedCount = 0

  filesToProcess.forEach((file) => {
    if (file.size > props.MAX_SIZE) {
      emit('show-toast', {
        type: 'error',
        message: `檔案大小超過 ${props.MAX_SIZE / 1024 / 1024}MB`,
      })
      return
    }

    const reader = new FileReader()
    reader.onload = (e) => {
      newImages.push({
        file,
        src: e.target.result,
        isMain: newImages.length === 0, // 第一張設為主圖
      })
      loadedCount++

      // 只在所有文件都處理完後更新一次
      if (loadedCount === filesToProcess.length) {
        images.value = newImages
      }
    }
    reader.readAsDataURL(file)
  })
}

const removeItem = (index) => {
  if (props.isDisabled) return

  // 淺拷貝一份圖片數組，然後刪除指定索引的圖片
  const newImages = [...images.value]
  const removedImage = newImages.splice(index, 1)[0]

  // 當主圖刪除時，將第一張圖設為主圖
  if (removedImage.isMain && newImages.length > 0) {
    newImages[0].isMain = true
  }

  images.value = newImages
}

const setMainImage = (index) => {
  if (props.isDisabled) return

  const newImages = [...images.value]

  newImages.forEach((image) => (image.isMain = false))

  const mainImage = newImages.splice(index, 1)[0]
  mainImage.isMain = true

  newImages.unshift(mainImage) // 將主圖移到第一位

  images.value = newImages
}
</script>

<style lang="scss" scoped>
label {
  display: block;
  margin-bottom: 10px;
  font-weight: 500;
  font-size: 1.125rem;
  color: #2e3748;
}

.image-uploader {
  display: flex;
  gap: 15px;
}

.previews-container {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;

  .image-preview {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px #0000001a;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .delete-btn {
      position: absolute;
      top: 5px;
      right: 5px;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #ff0000b3;
      color: #fff;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;

      i {
        font-size: 1rem;
      }

      &:hover {
        background: #ff0000e6;
      }
    }

    .main-label {
      position: absolute;
      bottom: 5px;
      left: 5px;
      padding: 2px 6px;
      background: rgba(46, 55, 72, 0.7);
      color: #fff;
      border-radius: 4px;
      font-size: 0.75rem;
    }

    .set-main-btn {
      position: absolute;
      bottom: 5px;
      left: 5px;
      padding: 4px;
      background: rgba(255, 255, 255, 0.7);
      color: #2e3748;
      border: none;
      border-radius: 4px;
      cursor: pointer;

      i {
        font-size: 0.875rem;
      }

      &:hover {
        background: rgba(255, 255, 255, 0.9);
      }
    }
  }
}

.upload-container {
  display: flex;
  align-items: center;

  input {
    display: none;
  }
}

.upload-box {
  width: 120px;
  height: 120px;
  border: 2px dashed #ccc;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  color: #2e3748;

  &:hover {
    border-color: #2e3748;
    background-color: #f5f6fa;
  }

  &.dragging {
    color: #3498db;
    border-color: #3498db;
    background-color: rgba(52, 152, 219, 0.1);
  }

  &.disabled {
    opacity: 0.5;
    cursor: not-allowed;

    &:hover {
      border-color: #ccc;
      background-color: transparent;
    }
  }

  i {
    font-size: 1.5rem;
    margin-bottom: 5px;
  }

  span {
    font-size: 0.8rem;
  }
}
</style>
