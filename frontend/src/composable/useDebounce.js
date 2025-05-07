export function useDebounce() {
  const debounce = (fn, delay) => {
    let debounceTimer

    return (...args) => {
      clearTimeout(debounceTimer) // 清除前一次的定時器
      debounceTimer = setTimeout(() => {
        fn(...args)
      }, delay)
    }
  }

  return { debounce }
}
