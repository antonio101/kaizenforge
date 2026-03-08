import type { LoginResponse } from '@/features/auth/types/loginResponse'
import type { Session } from '@/features/auth/types/session'

export function mapLoginResponseToSession(response: LoginResponse): Session {
  return {
    accessToken: response.accessToken,
    tokenType: response.tokenType,
    expiresAt: response.expiresAt,
    user: response.user,
  }
}
