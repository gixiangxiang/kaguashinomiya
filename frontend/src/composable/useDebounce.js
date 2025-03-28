export function useDebounce() {
  const debounce = (fn, delay) => {
    let debounceTimer

    return (...args) => {
      clearTimeout(debounceTimer)
      setTimeout(() => {
        fn(...args)
      }, delay)
    }
  }

  return { debounce }
}
