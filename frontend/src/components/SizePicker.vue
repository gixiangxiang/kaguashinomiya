<template>
  <label class="title">{{ label }}</label>

  <div class="options">
    <label v-for="option in options" :key="option">
      <input type="checkbox" :value="option" :disabled="disabled" v-model="checkboxModel" />
      <span>{{ option }}</span>
    </label>
  </div>
</template>

<script setup>
const checkboxModel = defineModel('checkboxModel', {
  type: Array,
  default: () => [],
})

const props = defineProps({
  options: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})
</script>

<style lang="scss" scoped>
.title {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  font-size: 1.125rem;
  color: #2e3748;
}
.options {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;

  label {
    display: flex;
    align-items: center;

    input {
      cursor: pointer;
      width: 16px;
      height: 16px;
      margin-right: 5px;
    }

    span {
      cursor: pointer;
      color: #2e3748;
      font-size: 1rem;
    }
  }
}
</style>
