# KaguyaShinomiya

這是一個使用 Vue 3 + Laravel 構建的商品展示。

## 技術棧

### Frontend

- **Vue 3** - 使用 Composition API 和 `<script setup>` 語法
- **Vite** - 快速的構建工具和開發服務器
- **Vue Router** - 單頁應用路由管理
- **Pinia** - 狀態管理
- **Sass** - CSS 預處理器
- **Axios** - HTTP 客戶端
- **Boxicons** - 圖標庫

### Backend

- **Laravel** - PHP 框架
- **MySQL** - 數據庫
- **RESTful API** - API 設計規範

## 📁 專案架構

```
frontend/
├── src/
│   ├── assets/           # 靜態資源
│   │   └── styles/       # 樣式文件
│   │       ├── main.scss # 主樣式入口
│   │       ├── _reset.scss # CSS Reset
│   │       └── mixins.scss # SCSS 混合器
│   ├── components/       # 可重用組件
│   │   ├── cart/         # 購物車相關組件
│   │   ├── product/      # 產品相關組件
│   │   └── ...          # 其他通用組件
│   ├── composable/       # 組合式函數
│   ├── server/           # API 相關
│   │   └── api/          # API 接口
│   ├── stores/           # Pinia 狀態管理
│   ├── views/            # 頁面組件
│   └── routes.js         # 路由配置
└── vite.config.js        # Vite 配置
```

## 🧩 組件設計

### 模組化架構設計

採用模組化組件設計，將功能劃分為可重用的獨立組件：

#### 1. 基礎通用組件

- [`BaseInput`](src/components/BaseInput.vue) - 通用輸入組件
- [`ToastMessage`](src/components/ToastMessage.vue) - 提示訊息組件
- [`QuantitySelector`](src/components/QuantitySelector.vue) - 數量選擇器
- [`SearchInput`](src/components/SearchInput.vue) - 搜尋輸入組件

#### 2. 產品功能組件

- [`ProductDisplay`](src/components/product/ProductDisplay.vue) - 產品詳情展示
- [`ProductList`](src/components/product/ProductList.vue) - 產品列表
- [`ProductEditor`](src/components/product/ProductEditor.vue) - 產品編輯器

#### 3. 購物車組件

- [`CartItem`](src/components/cart/CartItem.vue) - 購物車項目
- [`CartSummary`](src/components/cart/CartSummary.vue) - 購物車摘要
- [`EmptyCart`](src/components/cart/EmptyCart.vue) - 空購物車狀態

#### 4. 表單組件

- [`ColorSelector`](src/components/ColorSelector.vue) - 顏色選擇器
- [`SizePicker`](src/components/SizePicker.vue) - 尺寸選擇器
- [`ImageUploader`](src/components/ImageUploader.vue) - 圖片上傳組件

### 組件設計單一職責原則

每個組件都有明確的單一功能，例如：

- `QuantitySelector` 只負責數量選擇
- `ColorSelector` 只負責顏色選擇
- `ToastMessage` 只負責訊息提示
