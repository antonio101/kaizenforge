import type { PropsWithChildren } from 'react'
import type { Session } from '@/features/auth/types/session'
import {
  createContext,
  useCallback,
  useContext,
  useMemo,
  useState,
} from 'react'

import { authSessionStorage } from '@/infra/storage/authSessionStorage'

type SessionContextValue = {
  session: Session | null
  isAuthenticated: boolean
  isSessionHydrated: boolean
  handleSetSession: (nextSession: Session) => void
  handleClearSession: () => void
}

const SessionContext = createContext<SessionContextValue | null>(null)

export function SessionProvider({ children }: PropsWithChildren) {
  const [session, setSession] = useState<Session | null>(() =>
    authSessionStorage.getSession()
  )

  const handleSetSession = useCallback((nextSession: Session) => {
    setSession(nextSession)
    authSessionStorage.setSession(nextSession)
  }, [])

  const handleClearSession = useCallback(() => {
    setSession(null)
    authSessionStorage.clearSession()
  }, [])

  const value = useMemo<SessionContextValue>(
    () => ({
      session,
      isAuthenticated: session !== null,
      isSessionHydrated: true,
      handleSetSession,
      handleClearSession,
    }),
    [session, handleSetSession, handleClearSession]
  )

  return <SessionContext.Provider value={value}>{children}</SessionContext.Provider>
}

export function useSession() {
  const context = useContext(SessionContext)

  if (!context) {
    throw new Error('useSession must be used within SessionProvider')
  }

  return context
}
