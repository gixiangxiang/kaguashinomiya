<template>
  <a @click="changeShowEditor" class="add-btn"> + </a>

  <ToastMessage :toast="toast" />

  <section v-if="showEditor" class="product-editor-wrapper">
    <div class="product-editor">
      <div class="product-editor__header">
        <h2>{{ editMode ? '編輯商品' : '新增產品' }}</h2>
        <i @click="changeShowEditor" class="bx bx-x-circle"></i>
      </div>

      <form @submit.prevent class="product-editor__form" enctype="multipart/form-data">
        <div class="form-group">
          <ImageUploader
            v-model:images="newProduct.images"
            :label="'商品圖上傳'"
            :isDisabled="isLoading"
            :maxImages="5"
            :MAX_SIZE="1 * 1024 * 1024"
            @show-toast="handleShowToast"
          />
        </div>

        <div class="form-group">
          <BaseInput
            v-model:inputModel="newProduct.name"
            :placeholder="'請輸入商品名稱...'"
            :label="'商品名稱'"
            :disabled="isLoading"
          />
        </div>

        <div class="form-group">
          <BaseInput
            v-model:inputModel="newProduct.price"
            :placeholder="'請輸入商品價格...'"
            :label="'商品價格 (NT$)'"
            type="number"
            :disabled="isLoading"
          />
        </div>

        <div class="form-group">
          <BaseInput
            v-model:inputModel="newProduct.description"
            :placeholder="'請輸入商品描述...'"
            :label="'商品描述'"
            type="textarea"
            :disabled="isLoading"
          />
        </div>

        <div class="form-group">
          <SizePicker
            :label="'可選尺寸'"
            :options="productSizeOptions"
            v-model:checkboxModel="newProduct.size"
            :disabled="isLoading"
          />
        </div>

        <div class="form-group">
          <ColorSelector
            :label="'可選顏色'"
            :selectedColor="newProduct.colors"
            v-model:colorSelectorModel="currentColor"
            @add-color="addSelectedColor(currentColor)"
            @remove-color="removeSelectedColor($event)"
            :disabled="isLoading"
          />
        </div>

        <div class="form-actions">
          <button @click="clearForm" type="button" class="clear-btn">清除</button>
          <button
            @click="submitForm"
            type="submit"
            class="save-btn"
            :disabled="props.editMode && !hasChanges"
            :class="{ disabled: props.editMode && !hasChanges }"
          >
            <span v-if="!isLoading">儲存商品</span>
            <span v-else class="loading-text">
              {{ editMode ? '新增中' : '新增中' }}
              <span class="spinner"></span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup>
import BaseInput from '../BaseInput.vue'
import SizePicker from '../SizePicker.vue'
import ColorSelector from '../ColorSelector.vue'
import ToastMessage from '../ToastMessage.vue'
import ImageUploader from '../ImageUploader.vue'
import { reactive, ref, watch } from 'vue'

const emit = defineEmits(['submit-product', 'update-product'])

const props = defineProps({
  isLoading: {
    type: Boolean,
    default: false,
  },
  lastSubmitResult: {
    type: Object,
    default: null,
  },
  editMode: {
    type: Boolean,
    default: false,
  },
  productToEdit: {
    type: Object,
    default: null,
  },
})

const newProduct = reactive({
  name: '',
  colors: [],
  size: [],
  description: '',
  price: null,
  images: [],
})

const toast = reactive({
  type: '',
  message: '',
  show: false,
})

const productSizeOptions = ref(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'])
const currentColor = ref('')
const showEditor = ref(false)
const originalProduct = ref(null) // 用於存儲原始產品數據
const hasChanges = ref(false) // 用於檢查是否有變更

// 初始化數據
watch(
  () => props.productToEdit,
  (editedProduct) => {
    if (props.editMode && editedProduct) {
      newProduct.name = editedProduct.name
      newProduct.price = editedProduct.price
      newProduct.description = editedProduct.description
      newProduct.size = [...editedProduct.size]
      newProduct.colors = [...editedProduct.colors]

      // 複製圖片陣列
      if (editedProduct.images) {
        newProduct.images = JSON.parse(JSON.stringify(editedProduct.images))
      }

      // 保存原始資料副本之後比較
      originalProduct.value = JSON.parse(
        JSON.stringify({
          name: editedProduct.name,
          price: editedProduct.price,
          description: editedProduct.description,
          size: [...editedProduct.size],
          colors: [...editedProduct.colors],
          images: JSON.parse(JSON.stringify(editedProduct.images)),
        })
      )

      hasChanges.value = false // 初始狀態無變化
      showEditor.value = true
    }
  },
  { immediate: true }
)

// 偵測變化
watch(
  () => JSON.stringify(newProduct),
  () => {
    if (props.editMode && originalProduct.value) {
      const currentValue = JSON.stringify({
        name: newProduct.name,
        price: newProduct.price,
        description: newProduct.description,
        size: [...newProduct.size],
        colors: [...newProduct.colors],
        images: JSON.parse(JSON.stringify(newProduct.images)),
      })

      const originalValue = JSON.stringify(originalProduct.value)
      hasChanges.value = currentValue !== originalValue
    } else {
      hasChanges.value = true // 如果不是編輯模式，則始終設置為 true
    }
  },
  { deep: true }
)

watch(
  () => props.lastSubmitResult,
  (newResult) => {
    if (!newResult) return

    if (newResult.success) {
      clearForm()
      toastShow('success', props.editMode ? '商品編輯成功' : '商品新增成功')

      showEditor.value = false
    } else {
      toastShow('error', props.editMode ? '商品編輯失敗' : '商品新增失敗')
    }
  },
  { deep: true }
)

const submitForm = () => {
  if (
    !newProduct.name ||
    !newProduct.price ||
    !newProduct.description ||
    !newProduct.size.length ||
    !newProduct.colors.length ||
    !newProduct.images.length
  ) {
    toastShow('error', '請填寫所有欄位')
    return
  }

  if (!props.editMode) {
    emit('submit-product', newProduct)
  } else {
    emit('update-product', { ...newProduct, id: props.productToEdit.id })
  }
}

const toastShow = (type, message) => {
  toast.type = type
  toast.message = message
  toast.show = true
  setTimeout(() => (toast.show = false), 2000)
}

const handleShowToast = (toastData) => {
  // 圖片上傳張數超過限制顯示訊息
  toastShow(toastData.type, toastData.message)
}

const addSelectedColor = (color) => {
  if (!color) return
  if (!newProduct.colors.includes(color)) {
    newProduct.colors.push(color)
  }
}

const removeSelectedColor = (index) => {
  newProduct.colors.splice(index, 1)
}

const changeShowEditor = () => {
  showEditor.value = !showEditor.value

  if (!showEditor.value) {
    // 如果是編輯模式，發送取消編輯事件
    if (props.editMode) {
      emit('cancel-edit')
    }
    clearForm()
  }
}

const clearForm = () => {
  newProduct.name = ''
  newProduct.colors = []
  newProduct.size = []
  newProduct.description = ''
  newProduct.price = null
  newProduct.images = []
}
</script>

<style lang="scss" scoped>
.add-btn,
.add-btn:focus {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 55px;
  height: 55px;
  border-radius: 50%;
  border: none;
  background: #2e3748;
  color: #fff;
  font-size: 1.5rem;
  transition:
    box-shadow 400ms cubic-bezier(0.2, 0, 0.7, 1),
    transform 200ms cubic-bezier(0.2, 0, 0.7, 1);

  &:hover {
    transform: rotate(45deg);
    box-shadow:
      0 0 1px 10px #2e374866,
      0 0 1px 25px #2e374833,
      0 0 1px 40px #2e37481a;
  }
}

.product-editor-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #00000080;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
}
.product-editor {
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 5px 15px #00000033;
  overflow-y: auto;
  flex-direction: column;

  &__header {
    position: sticky;
    top: 0;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #ccc;
    background-color: #fff;
    padding: 20px;

    h2 {
      color: #2e3748;
      font-size: 1.5rem;
      font-weight: 600;
    }

    i {
      cursor: pointer;
      font-size: 1.45rem;
      transition: color 0.15s ease-in-out;

      &:hover {
        color: #ff4757;
      }
    }
  }

  &__form {
    padding: 20px;

    .form-group {
      margin-bottom: 20px;
    }
  }
}

.form-actions {
  display: flex;
  gap: 10px;
  align-items: center;
  justify-content: flex-end;

  button {
    cursor: pointer;
    background-color: #2e3748;
    padding: 10px 20px;
    color: #fff;
    border-radius: 6px;
    transition: all 0.2s;
  }

  .clear-btn {
    background-color: #f1f2f6;
    color: #2e3748;
    border: 1px solid #ddd;

    &:hover {
      background-color: #e2e4e9;
    }
  }

  .save-btn {
    background-color: #2e3748;
    color: white;
    border: none;

    &.disabled {
      opacity: 0.6;
      cursor: no-drop;
    }

    .loading-text {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .spinner {
      display: inline-block;
      width: 18px;
      height: 18px;
      border: 2px solid #ffffff;
      border-radius: 50%;
      border-top-color: transparent;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  }
}
</style>
