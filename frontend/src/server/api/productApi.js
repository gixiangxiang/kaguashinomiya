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
      const formData = new FormData()

      const jsonData = {
        name: product.name,
        price: product.price,
        description: product.description,
        size: product.size,
        colors: product.colors,
      }

      formData.append('jsonData', JSON.stringify(jsonData))

      product.images.forEach((image, index) => {
        formData.append(`image${index}`, image.file)
        formData.append(`isMain${index}`, image.isMain)
      })

      const headers = {
        'Content-Type': 'multipart/form-data',
      }
      await console.log('formData', formData)

      const response = await axios.post('/api/api/product/add', formData, headers)
      return response.data
    } catch (error) {
      throw error
    }
  },
}
