import { queryKeys } from '@/config/queryKeys'

export const authQueryKeys = {
  all: [...queryKeys.auth] as const,
  me: [...queryKeys.auth, 'me'] as const,
} as const
