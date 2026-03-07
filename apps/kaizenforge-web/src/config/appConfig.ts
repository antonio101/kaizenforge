import { env } from '@/infra/config/env'

export const appConfig = {
  apiBaseUrl: env.apiBaseUrl,
  appEnv: env.appEnv,
  apiTimeoutMs: 10000,
} as const
