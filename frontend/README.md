# KaguyaShinomiya

這是一個使用 Vue 3 + Laravel 構建的商品展示。

## 📌 技術棧

### Frontend

- **Vue 3**
- **Vite**
- **Vue Router**
- **Pinia**
- **Sass**
- **Axios**
- **Boxicons**

### Backend

- **Laravel**
- **MySQL**
- **RESTful API**

## 📌 專案架構

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

## 📌 核心功能

### 商品瀏覽功能

- **商品展示**
  - 可以看多張商品圖片，點縮圖切換主圖
  - 圖片載入使用了懶加載，避免一次載入太多圖片
  - 手機上可以滑動切換圖片
- **商品搜尋**
  - 輸入關鍵字就能找商品，沒什麼特別的
  - 搜尋結果會即時顯示
- **商品詳情**
  - 顯示商品名稱、價格、描述這些基本資訊
  - 價格有加千分位逗號，看起來比較清楚
  - 可以選擇顏色和尺寸

### 購物車系統

- **加入購物車**
  - 選好顏色尺寸後可以加入購物車
  - 有做防抖處理，避免使用者連點好幾次
  - 會檢查是否已選擇規格，沒選的話會提醒
- **購物車管理**
  - 可以調整數量、刪除商品
  - 數量一改，價格就會自動重算
- **資料同步**
  - 用 Pinia 管理購物車狀態，確保不同頁面資料一致
  - 總價和商品數量會自動計算
- **本地存儲**
  - 購物車資料存在 localStorage
  - 重新整理頁面不會丟失，但清除瀏覽器資料就會不見

### 商品客製化

- **顏色選擇**
  - 做了一個顏色選擇器，可以看到顏色預覽
  - 選中的顏色會有視覺回饋
- **尺寸選擇**
  - 簡單的尺寸選項，從後端動態載入
- **數量調整**
  - 用 `QuantitySelector` 組件，支援手動輸入或按鈕調整
  - 設定了最小最大值限制

### 後台管理功能

- **商品管理**
  - 可以新增、編輯、刪除商品
  - 表單有基本驗證，必填欄位不能空白
  - 編輯時會比較前後資料是否有變更
- **圖片上傳**
  - 支援拖拽上傳，也可以點擊選檔案
  - 會壓縮圖片減少檔案大小
  - 可以設定主圖和副圖

## 📌 技術實作

### 狀態管理

- **Pinia Store** - 用來管理購物車狀態
- **響應式更新** - 狀態改變時 UI 會自動更新
- **持久化** - 重要資料會存到 localStorage

### 性能處理

- **防抖處理** - 搜尋和按鈕點擊有防抖，避免頻繁 API 調用

### API 架構

- **RESTful 設計** - API 照標準設計，增刪查改都有
- **錯誤處理** - 用 Axios 攔截器統一處理錯誤
- **圖片路徑處理** - 自動幫圖片加上完整的 URL 路徑

## 📌 組件設計

### 組件設計

- **模組化拆分** - 把功能拆成小組件，方便重複使用
- **單一職責** - 每個組件只做一件事，比較好維護

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
