import { afterEach, describe, expect, it } from 'vitest'

import { storageKeys } from '@/config/storageKeys'
import type { Session } from '@/features/auth/types/session'

import { authSessionStorage } from './authSessionStorage'

const validSession = {
  accessToken: 'token-123',
  tokenType: 'Bearer',
  expiresAt: '2026-04-05T12:00:00Z',
  user: {
    id: 'user-1',
    email: 'user@example.com',
    roles: ['ROLE_USER'],
  },
} satisfies Session

describe('authSessionStorage', () => {
  afterEach(() => {
    window.localStorage.clear()
  })

  it('returns stored session when valid', () => {
    window.localStorage.setItem(storageKeys.session, JSON.stringify(validSession))

    expect(authSessionStorage.getSession()).toEqual(validSession)
  })

  it('removes invalid shape and returns null', () => {
    window.localStorage.setItem(
      storageKeys.session,
      JSON.stringify({
        ...validSession,
        user: { ...validSession.user, email: 'not-an-email' },
      })
    )

    expect(authSessionStorage.getSession()).toBeNull()
    expect(window.localStorage.getItem(storageKeys.session)).toBeNull()
  })

  it('removes malformed JSON and returns null', () => {
    window.localStorage.setItem(storageKeys.session, '{invalid-json')

    expect(authSessionStorage.getSession()).toBeNull()
    expect(window.localStorage.getItem(storageKeys.session)).toBeNull()
  })

  it('persists session', () => {
    authSessionStorage.setSession(validSession)

    expect(window.localStorage.getItem(storageKeys.session)).toEqual(
      JSON.stringify(validSession)
    )
  })

  it('clears session', () => {
    window.localStorage.setItem(storageKeys.session, JSON.stringify(validSession))

    authSessionStorage.clearSession()

    expect(window.localStorage.getItem(storageKeys.session)).toBeNull()
  })
})
