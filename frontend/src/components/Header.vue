<template>
  <header>
    <div class="header-container">
      <h1 @click="scrollToTop" class="logo">進撃の巨人</h1>
      <nav class="nav-links">
        <router-link class="nav-link" to="/">首頁</router-link>
        <router-link class="nav-link cart-link" to="/cart">
          <i class="bx bx-cart"></i>
          購物車
        </router-link>
      </nav>
    </div>
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
  background-color: #fff;
  box-shadow:
    0 2px 4px #0000001a,
    0 1px 2px #0000000f;
}

.header-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

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

.nav-links {
  display: flex;
  gap: 20px;
}

.nav-link {
  text-decoration: none;
  color: #2d3748;
  font-weight: 500;
  transition: color 0.3s ease;
  display: flex;
  align-items: center;
  gap: 5px;

  &:hover {
    color: #5a6b7e;
  }
}

.nav-link {
  text-decoration: none;
  color: #2d3748;
  font-weight: 500;
  transition:
    color 0.3s ease,
    transform 0.3s ease;
  display: flex;
  align-items: center;
  gap: 5px;
  position: relative;
  padding: 8px 0;

  &::before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, #3a4a5e 0%, #5a6b7e 70%);
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1);
    border-radius: 2px;
  }

  &:hover {
    color: #5a6b7e;

    &::before {
      transform: scaleX(0.5);
    }
  }
}

.router-link-exact-active {
  color: #3a4a5e;
  font-weight: 600;

  &::before {
    transform: scaleX(1);
    transform-origin: left;
    height: 2px;
    box-shadow: 0 1px 2px #3a4a5e33;
  }
}

@media (max-width: 768px) {
  .header-container {
    padding: 0 15px;
  }

  .logo {
    font-size: 1.5rem;
  }
}
</style>
