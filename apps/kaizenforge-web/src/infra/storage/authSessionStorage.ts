import type { Session } from '@/features/auth/types/session'
import { storageKeys } from '@/config/storageKeys'
import { sessionSchema } from '@/features/auth/schemas/sessionSchema'
import { browserStorage } from '@/infra/storage/browserStorage'

export type AuthSessionStorage = {
  getSession(): Session | null
  setSession(session: Session): void
  clearSession(): void
}

export const authSessionStorage: AuthSessionStorage = {
  getSession() {
    const storedSession = browserStorage.getItem<unknown>(storageKeys.session)

    const parsedSession = sessionSchema.safeParse(storedSession)

    if (!parsedSession.success) {
      browserStorage.removeItem(storageKeys.session)
      return null
    }

    return parsedSession.data
  },

  setSession(session: Session) {
    browserStorage.setItem(storageKeys.session, session)
  },

  clearSession() {
    browserStorage.removeItem(storageKeys.session)
  },
}
