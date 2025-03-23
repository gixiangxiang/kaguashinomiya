<template>
  <div class="toast" v-if="toast.show">
    <div class="toast-content" :class="toast.type">
      <i class="bx" :class="toast.type === 'success' ? 'bx-check-circle' : 'bx-error-circle'"></i>
      <span>{{ toast.message }}</span>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  toast: {
    type: Object,
  },
})
</script>

<style lang="scss" scoped>
.toast {
  position: fixed;
  z-index: 9999;
  top: 30px;
  right: 30px;
  max-width: 350px;
  min-width: 300px;
  pointer-events: none;

  .toast-content {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid;
    box-shadow: 0 0 10px 5px #00000016;

    &.success {
      border-color: #52c41a;
      color: #52c41a;
      background-color: #f6ffed;
    }

    &.error {
      border-color: #ff4d4f;
      color: #ff4d4f;
      background-color: #fff0f0;
    }

    i {
      font-size: 1.125rem;
    }

    span {
      font-size: 0.875rem;
      letter-spacing: 3px;
    }
  }
}
</style>
