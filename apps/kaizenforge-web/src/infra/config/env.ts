export type AppEnv = 'development' | 'test' | 'production'

export type Env = {
  apiBaseUrl: string
  appEnv: AppEnv
}

function normalizeApiBaseUrl(value: string) {
  return value.replace(/\/+$/, '')
}

function getApiBaseUrl() {
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL

  if (!apiBaseUrl || typeof apiBaseUrl !== 'string') {
    throw new Error('Missing VITE_API_BASE_URL')
  }

  return normalizeApiBaseUrl(apiBaseUrl)
}

function getAppEnv(): AppEnv {
  const mode = import.meta.env.MODE

  if (mode === 'production' || mode === 'test') {
    return mode
  }

  return 'development'
}

export const env: Env = {
  apiBaseUrl: getApiBaseUrl(),
  appEnv: getAppEnv(),
}
