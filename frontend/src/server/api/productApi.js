import axios from 'axios'

export const productApi = {
  // 獲取所有產品
  getAllProducts: async () => {
    try {
      const response = await axios.get('/api/api/products-json/all')
      return response.data.products
    } catch (error) {
      throw error
    }
  },

  // 搜尋產品
  searchProducts: async (keyword) => {
    try {
      const response = await axios.get(`/api/api/product/search`, {
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

      const response = await axios.post('/api/api/product/add', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      return response.data
    } catch (error) {
      throw error
    }
  },
}
