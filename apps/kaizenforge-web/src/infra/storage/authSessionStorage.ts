import type { Session } from '@/features/auth/types/session'
import { storageKeys } from '@/config/storageKeys'
import { browserStorage } from '@/infra/storage/browserStorage'

export type AuthSessionStorage = {
  getSession(): Session | null
  setSession(session: Session): void
  clearSession(): void
}

function isSession(value: unknown): value is Session {
  if (!value || typeof value !== 'object') {
    return false
  }

  const candidate = value as Record<string, unknown>

  return (
    typeof candidate.accessToken === 'string' &&
    candidate.accessToken.trim().length > 0
  )
}

export const authSessionStorage: AuthSessionStorage = {
  getSession() {
    const storedSession = browserStorage.getItem<unknown>(storageKeys.session)

    if (!isSession(storedSession)) {
      browserStorage.removeItem(storageKeys.session)
      return null
    }

    return storedSession
  },

  setSession(session: Session) {
    browserStorage.setItem(storageKeys.session, session)
  },

  clearSession() {
    browserStorage.removeItem(storageKeys.session)
  },
}
