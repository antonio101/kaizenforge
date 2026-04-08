import { describe, expect, it } from 'vitest'

import type { LoginResponse } from '@/features/auth/types/loginResponse'

import { mapLoginResponseToSession } from './mapLoginResponseToSession'

describe('mapLoginResponseToSession', () => {
  it('maps login response to session shape', () => {
    const response = {
      accessToken: 'token-123',
      tokenType: 'Bearer',
      expiresAt: '2026-04-05T12:00:00Z',
      user: {
        id: 'user-1',
        email: 'user@example.com',
        roles: ['ROLE_USER'],
      },
    } satisfies LoginResponse

    expect(mapLoginResponseToSession(response)).toEqual(response)
  })
})
