export type BrowserStorage = {
  getItem<T>(key: string): T | null
  setItem<T>(key: string, value: T): void
  removeItem(key: string): void
}

function isLocalStorageAvailable() {
  return typeof window !== 'undefined' && typeof window.localStorage !== 'undefined'
}

export const browserStorage: BrowserStorage = {
  getItem<T>(key: string) {
    if (!isLocalStorageAvailable()) {
      return null
    }

    try {
      const rawValue = window.localStorage.getItem(key)

      if (!rawValue) {
        return null
      }

      return JSON.parse(rawValue) as T
    } catch {
      return null
    }
  },

  setItem<T>(key: string, value: T) {
    if (!isLocalStorageAvailable()) {
      return
    }

    try {
      window.localStorage.setItem(key, JSON.stringify(value))
    } catch {
      
    }
  },

  removeItem(key: string) {
    if (!isLocalStorageAvailable()) {
      return
    }

    try {
      window.localStorage.removeItem(key)
    } catch {
      
    }
  },
}
