import type { PropsWithChildren } from 'react'
import {
  createContext,
  useCallback,
  useContext,
  useMemo,
  useState,
} from 'react'

type Session = {
  accessToken: string
} | null

type SessionContextValue = {
  session: Session
  isAuthenticated: boolean
  isSessionHydrated: boolean
  handleSetSession: (nextSession: Exclude<Session, null>) => void
  handleClearSession: () => void
}

const SessionContext = createContext<SessionContextValue | null>(null)

export function SessionProvider({ children }: PropsWithChildren) {
  const [session, setSession] = useState<Session>(null)

  const [isSessionHydrated] = useState(true)

  const handleSetSession = useCallback(
    (nextSession: Exclude<Session, null>) => {
      setSession(nextSession)
    },
    []
  )

  const handleClearSession = useCallback(() => {
    setSession(null)
  }, [])

  const value = useMemo<SessionContextValue>(
    () => ({
      session,
      isAuthenticated: session !== null,
      isSessionHydrated,
      handleSetSession,
      handleClearSession,
    }),
    [session, isSessionHydrated, handleSetSession, handleClearSession]
  )

  return (
    <SessionContext.Provider value={value}>
      {children}
    </SessionContext.Provider>
  )
}

export function useSession() {
  const context = useContext(SessionContext)

  if (!context) {
    throw new Error('useSession must be used within SessionProvider')
  }

  return context
}