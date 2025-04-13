export function useMillimeters() {
  const millimeters = (num) => {
    return String(num).replace(/(\d)(?=(\d{3})+$)/g, '$1,')
  }

  return { millimeters }
}
