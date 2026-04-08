import { render, screen, waitFor } from '@testing-library/react'
import { afterEach, beforeEach, describe, expect, it } from 'vitest'
import { delay, http, HttpResponse } from 'msw'

import { App } from '@/app/App'
import { AppProviders } from '@/app/providers/AppProviders'
import type { Session } from '@/features/auth/types/session'
import { authSessionStorage } from '@/infra/storage/authSessionStorage'
import { server } from '@/test/msw/server'

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

const authMeUrl = `${import.meta.env.VITE_API_BASE_URL}/auth/me`

function renderApplication() {
  return render(
    <AppProviders>
      <App />
    </AppProviders>
  )
}

describe('auth bootstrap integration', () => {
  beforeEach(() => {
    window.history.replaceState({}, '', '/')
    window.localStorage.clear()
  })

  afterEach(() => {
    window.history.replaceState({}, '', '/')
    window.localStorage.clear()
  })

  it('restores the authenticated experience when a persisted session is still valid', async () => {
    authSessionStorage.setSession(validSession)

    server.use(
      http.get(authMeUrl, async () => {
        await delay(50)

        return HttpResponse.json(validSession.user, {
          status: 200,
        })
      })
    )

    renderApplication()

    expect(
      screen.getByRole('heading', { name: 'Preparing your workspace' })
    ).toBeInTheDocument()

    expect(
      await screen.findByRole('heading', {
        name: 'Application home placeholder',
      })
    ).toBeInTheDocument()

    expect(screen.getByText('user@example.com')).toBeInTheDocument()
    expect(authSessionStorage.getSession()).toEqual(validSession)
  })

  it('clears the persisted session and redirects to login when /auth/me is unauthorized', async () => {
    authSessionStorage.setSession(validSession)

    server.use(
      http.get(authMeUrl, async () => {
        await delay(50)

        return HttpResponse.json(
          { message: 'Unauthorized' },
          {
            status: 401,
          }
        )
      })
    )

    renderApplication()

    expect(
      screen.getByRole('heading', { name: 'Preparing your workspace' })
    ).toBeInTheDocument()

    expect(
      await screen.findByRole('heading', {
        name: 'Sign in to Kaizen Forge',
      })
    ).toBeInTheDocument()

    await waitFor(() => {
      expect(authSessionStorage.getSession()).toBeNull()
    })

    expect(
      screen.queryByRole('heading', {
        name: 'Application home placeholder',
      })
    ).not.toBeInTheDocument()
  })
})