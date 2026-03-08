import { httpClient } from '@/infra/http/httpClient'

import { loginResponseSchema } from '@/features/auth/schemas/loginResponseSchema'
import { logoutResponseSchema } from '@/features/auth/schemas/logoutResponseSchema'
import { meResponseSchema } from '@/features/auth/schemas/meResponseSchema'
import type { LoginRequest } from '@/features/auth/types/loginRequest'
import type { LoginResponse } from '@/features/auth/types/loginResponse'
import type { LogoutResponse } from '@/features/auth/types/logoutResponse'
import type { MeResponse } from '@/features/auth/types/meResponse'
import type { RequestConfig } from '@/infra/http/httpClient'

export const authApi = {
  async login(payload: LoginRequest, signal?: AbortSignal): Promise<LoginResponse> {
    const config: RequestConfig | undefined = signal ? { signal } : undefined

    const response = await httpClient.post<unknown, LoginRequest>(
      '/auth/login',
      payload,
      config
    )

    return loginResponseSchema.parse(response)
  },

  async me(signal?: AbortSignal): Promise<MeResponse> {
    const config: RequestConfig | undefined = signal ? { signal } : undefined

    const response = await httpClient.get<unknown>('/auth/me', config)

    return meResponseSchema.parse(response)
  },

  async logout(signal?: AbortSignal): Promise<LogoutResponse> {
    const config: RequestConfig | undefined = signal ? { signal } : undefined

    const response = await httpClient.post<unknown>('/auth/logout', undefined, config)

    return logoutResponseSchema.parse(response)
  },
}
