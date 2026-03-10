import { useQuery } from '@tanstack/react-query'

import { authQueryKeys } from '@/features/auth/constants/authQueryKeys'
import { useAuthSession } from '@/features/auth/hooks/useAuthSession'
import { authApi } from '@/features/auth/services/authApi'
import type { MeResponse } from '@/features/auth/types/meResponse'
import type { HttpError } from '@/infra/http/httpErrors'

type UseMeQueryResult = {
  user: MeResponse | null
  isLoading: boolean
  isFetching: boolean
  hasError: boolean
  error: HttpError | null
}

export function useMeQuery(): UseMeQueryResult {
  const { isAuthenticated, isSessionHydrated } = useAuthSession()

  const query = useQuery<MeResponse, HttpError>({
    queryKey: authQueryKeys.me,
    enabled: isSessionHydrated && isAuthenticated,
    staleTime: 60_000,
    retry(failureCount, error) {
      if (
        error.code === 'unauthorized' ||
        error.code === 'forbidden' ||
        error.code === 'canceled'
      ) {
        return false
      }

      return failureCount < 1
    },
    queryFn: ({ signal }) => authApi.me(signal),
  })

  return {
    user: query.data ?? null,
    isLoading: query.isLoading,
    isFetching: query.isFetching,
    hasError: query.isError,
    error: query.error ?? null,
  }
}
