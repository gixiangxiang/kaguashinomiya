<template>
  <label>{{ label }}</label>
  <textarea
    v-if="type === 'textarea'"
    :placeholder="placeholder"
    v-model="inputModel"
    required
  ></textarea>
  <input v-else :type="type" :placeholder="placeholder" v-model="inputModel" required />
</template>

<script setup>
const inputModel = defineModel('inputModel', {
  type: [String, Number],
  default: '',
})

const props = defineProps({
  label: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
  },
})
</script>

<style lang="scss" scoped>
label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  font-size: 1.125rem;
  color: #2e3748;
}
input {
  width: 100%;
  padding: 8px;
  outline: none;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 0.875rem;
  color: #2e3748;
  transition: border-color 0.15s ease;

  &:focus {
    border-color: #aaa;
  }

  &[type='number'] {
    -moz-appearance: textfield; /* Firefox */
    &::-webkit-outer-spin-button,
    &::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  }
}
textarea {
  width: 100%;
  height: 100px;
  padding: 8px;
  outline: none;
  resize: none;
  border: 1px solid #ddd;
  border-radius: 5px;

  &:focus {
    border-color: #aaa;
  }
}
</style>
