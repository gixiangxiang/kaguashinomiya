import axios from 'axios'

const apiClient = axios.create()

// 獲取環境變數中的圖片基礎URL
const imageBaseUrl = import.meta.env.VITE_IMAGE_BASE_URL

// 處理產品圖片路徑的函數
const processProductImages = (product) => {
  if (product && product.images && Array.isArray(product.images)) {
    product.images.forEach((img) => {
      img.src = `${imageBaseUrl}/${img.src}`
    })
  } else {
    throw new Error('無效的產品數據')
  }
  return product
}

apiClient.interceptors.response.use((response) => {
  if (response.data) {
    if (response.data.product) {
      // 處理單個產品的圖片路徑
      response.data.product = processProductImages(response.data.product)
    }

    // 處理多個產品的圖片路徑
    if (response.data.products) {
      if (Array.isArray(response.data.products)) {
        response.data.products = response.data.products.map((product) => {
          return processProductImages(product)
        })
      }
    }
  }
  return response
})

export const productApi = {
  // 獲取所有產品
  getAllProducts: async () => {
    try {
      const response = await apiClient.get('/api/api/products-json/all')
      return response.data.products
    } catch (error) {
      throw error
    }
  },

  // 搜尋產品
  searchProducts: async (keyword) => {
    try {
      const response = await apiClient.get(`/api/api/product/search`, {
        params: {
          keyword: keyword,
        },
      })
      return response.data.products
    } catch (error) {
      throw error
    }
  },

  // 新增產品
  addProduct: async (product) => {
    try {
      const formData = new FormData()

      const jsonData = {
        name: product.name,
        price: product.price,
        description: product.description,
        size: product.size,
        colors: product.colors,
      }

      const mainImage = product.images.find((img) => img.isMain)
      const otherImages = product.images.filter((img) => !img.isMain)

      formData.append('jsonData', JSON.stringify(jsonData))
      formData.append('mainImage', mainImage.file)
      otherImages.forEach((img, index) => {
        formData.append(`images[${index}]`, img.file)
      })

      const response = await apiClient.post('/api/api/product/add', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      return response.data
    } catch (error) {
      throw error
    }
  },

  // 編輯產品
  updateProduct: async (product) => {
    try {
      const formData = new FormData()

      const mainImage = product.images.find((img) => img.isMain)
      const otherImages = product.images.filter((img) => !img.isMain)

      // 計算要保留的現有副圖
      const retainedImages = otherImages
        .filter((img) => !img.file && img.src)
        .map((img) => img.src.replace(`${imageBaseUrl}/`, ''))

      const jsonData = {
        id: product.id,
        name: product.name,
        price: product.price,
        description: product.description,
        size: product.size,
        colors: product.colors,
        images: {
          // 如果主圖是現有圖片，傳遞檔名
          originalImage:
            mainImage && !mainImage.file && mainImage.src
              ? mainImage.src.replace(`${imageBaseUrl}/`, '')
              : '',
          retainedImages: retainedImages, // 保留的副圖檔名列表
        },
      }

      formData.append('jsonData', JSON.stringify(jsonData))

      // 處理新上傳的主圖檔案
      if (mainImage && mainImage.file) {
        formData.append('mainImage', mainImage.file)
      }

      // 處理新上傳的副圖檔案
      const newOtherImages = otherImages.filter((img) => img.file)
      newOtherImages.forEach((img, index) => {
        formData.append(`newImages[${index}]`, img.file)
      })

      const response = await apiClient.post('/api/api/product/update', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      return response.data
    } catch (error) {
      console.error('更新產品時發生錯誤:', error)
      if (error.response) {
        console.error('回應狀態:', error.response.status)
        console.error('回應資料:', error.response.data)
      }
      throw error
    }
  },
}
