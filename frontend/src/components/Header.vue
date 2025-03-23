<template>
  <header>
    <h1 @click="scrollToTop" class="logo">KaguyaShinomiya</h1>
  </header>
</template>

<script setup>
const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  })
}
</script>

<style lang="scss" scoped>
header {
  position: fixed;
  z-index: 100;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
  box-shadow:
    0 2px 4px #0000001a,
    0 1px 2px #0000000f;

  .logo {
    cursor: pointer;
    font-size: 1.875rem;
    font-weight: 600;
    line-height: 2.25;
    color: #2d3748;
    background: linear-gradient(90deg, #3a4a5e 0%, #5a6b7e 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;

    transition: all 0.3s ease;

    &:hover {
      transform: translateY(-1px);
      text-shadow: 0 2px 2px rgba(0, 0, 0, 0.05);
    }
  }
}
</style>
