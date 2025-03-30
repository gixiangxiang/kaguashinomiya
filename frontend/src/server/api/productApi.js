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

      const jsonData = {
        id: product.id,
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

      const response = await apiClient.post(`/api/api/product/update`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })

      return response.data
    } catch (error) {
      throw error
    }
  },
}
