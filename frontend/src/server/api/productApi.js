import axios from 'axios'

export const productApi = {
  // 獲取所有產品
  getAllProducts: async () => {
    try {
      const response = await axios.get('/api/api/products-json/all')
      return response.data.products
    } catch (error) {
      // console.error('獲取產品失敗:', error)
      throw error
    }
  },

  // 新增產品
  addProduct: async (product) => {
    try {
      const response = await axios.post('/api/api/product/add', product)
      return response.data
    } catch (error) {
      // console.error('新增產品失敗:', error)
      throw error
    }
  },
}
