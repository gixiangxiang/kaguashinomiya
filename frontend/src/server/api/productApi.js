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

  // 新增產品
  addProduct: async (product) => {
    try {
      const response = await axios.post('/api/api/product/add', product)
      return response.data
    } catch (error) {
      throw error
    }
  },
}
