import { useEffect } from 'react'

import { useAuthSession } from '@/features/auth/hooks/useAuthSession'
import { useMeQuery } from '@/features/auth/hooks/useMeQuery'

type UseAuthBootstrapResult = {
  isAuthReady: boolean
  isBootstrapping: boolean
}

export function useAuthBootstrap(): UseAuthBootstrapResult {
  const { session, isSessionHydrated, handleClearSession } = useAuthSession()
  const { isLoading, isFetching, error } = useMeQuery()

  const hasPersistedSession = isSessionHydrated && session !== null
  const isBootstrapping = hasPersistedSession && (isLoading || isFetching)

  useEffect(() => {
    if (!error) {
      return
    }

    if (error.code === 'unauthorized' || error.code === 'forbidden') {
      handleClearSession()
    }
  }, [error, handleClearSession])

  return {
    isAuthReady: isSessionHydrated && !isBootstrapping,
    isBootstrapping,
  }
}
