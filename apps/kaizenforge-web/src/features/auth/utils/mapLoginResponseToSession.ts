import type { LoginResponse } from '@/features/auth/types/loginResponse'
import type { Session } from '@/features/auth/types/session'

export function mapLoginResponseToSession(response: LoginResponse): Session {
  return {
    accessToken: response.token,
  }
}
