import { useSession } from '@/app/providers/SessionProvider'

export function useAuthSession() {
  const {
    session,
    isAuthenticated,
    isSessionHydrated,
    handleSetSession,
    handleClearSession,
  } = useSession()

  return {
    session,
    isAuthenticated,
    isSessionHydrated,
    handleSetSession,
    handleClearSession,
  }
}
