<template>
  <a @click="changeShowEditor" class="add-btn"> + </a>

  <ToastMessage :toast="toast" />

  <section v-if="showEditor" class="product-editor-wrapper">
    <div class="product-editor">
      <div class="product-editor__header">
        <h2>新增商品</h2>
        <i @click="changeShowEditor" class="bx bx-x-circle"></i>
      </div>

      <form @submit.prevent class="product-editor__form">
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
          <button @click="submitForm" type="submit" class="save-btn">
            <span v-if="!isLoading">儲存商品</span>
            <span v-else class="loading-text">
              新增中
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
import { reactive, ref, watch } from 'vue'

const emit = defineEmits(['submit-product'])

const props = defineProps({
  isLoading: {
    type: Boolean,
    default: false,
  },
  lastSubmitResult: {
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
  images: [
    {
      src: '123',
      isMain: true,
    },
  ],
})

const toast = reactive({
  type: 'success',
  message: '',
  show: false,
})

const productSizeOptions = ref(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'])
const currentColor = ref('')
const showEditor = ref(false)

const submitForm = () => {
  if (
    !newProduct.name ||
    !newProduct.price ||
    !newProduct.description ||
    !newProduct.size.length ||
    !newProduct.colors.length
  ) {
    toast.type = 'error'
    toast.message = '請填寫所有欄位'
    toast.show = true
    return
  }

  emit('submit-product', newProduct)
}

watch(
  () => props.lastSubmitResult,
  (newResult) => {
    if (!newResult) return

    if (newResult.success) {
      clearForm()
      toast.type = 'success'
      toast.message = '商品新增成功'
      toast.show = true

      showEditor.value = false
    } else {
      toast.type = 'error'
      toast.message = '商品新增失敗'
      toast.show = true
    }
  },
  { deep: true }
)

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
}

const clearForm = () => {
  newProduct.name = ''
  newProduct.colors = []
  newProduct.size = []
  newProduct.description = ''
  newProduct.price = null
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
  width: 60px;
  height: 60px;
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

  &__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #ccc;
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

    &:hover {
      background-color: #3a4a63;
    }
  }
}
</style>
