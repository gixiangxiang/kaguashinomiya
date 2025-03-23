<template>
  <label>{{ label }}</label>

  <!-- 已選顏色展示 -->
  <div class="selected-colors">
    <div
      v-for="(color, index) in selectedColor"
      :key="index"
      :style="{ backgroundColor: color === '#ffffff' ? '#ededed' : color }"
      :disabled="disabled"
    >
      <button @click="removeColor(index)" type="button">&times;</button>
      <span>{{ color }}</span>
    </div>
  </div>

  <!-- 顏色選擇器 -->
  <div class="color-selector">
    <input type="color" v-model="colorSelectorModel" />
    <button @click="addColor" type="button">添加顏色</button>
  </div>
</template>

<script setup>
const colorSelectorModel = defineModel('colorSelectorModel', {
  type: String,
  default: '#000000',
})

const props = defineProps({
  label: {
    type: String,
    default: '',
  },
  selectedColor: {
    type: Array,
    default: () => [],
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['add-color', 'remove-color'])

const addColor = () => {
  if (colorSelectorModel) {
    emit('add-color', colorSelectorModel)
  }
}

const removeColor = (colorIndex) => {
  if (colorIndex !== undefined) {
    emit('remove-color', colorIndex)
  }
}
</script>

<style lang="scss" scoped>
label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  font-size: 1.125rem;
  color: #2e3748;
}

.selected-colors {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin: 15px 0;

  div {
    display: flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    color: #fff;
    position: relative;

    span {
      margin-right: 24px;
      font-size: 0.875rem;
      text-shadow: 0 0 2px #00000080;
    }

    button {
      background: none;
      border: none;
      color: #fff;
      font-size: 1.125rem;
      cursor: pointer;
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%);
    }
  }
}

.color-selector {
  display: flex;
  align-items: center;
  gap: 15px;

  input {
    cursor: pointer;
    width: 60px;
    height: 40px;
    border: none;
    background-color: transparent;

    &::-webkit-color-swatch-wrapper {
      padding: 0;
    }

    &::-webkit-color-swatch {
      // border: none;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
  }

  button {
    background-color: #2e3748;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
  }
}
</style>
