import { queryKeys } from '@/config/queryKeys'

export const authQueryKeys = {
  all: [...queryKeys.auth] as const,
  session: [...queryKeys.auth, 'session'] as const,
} as const