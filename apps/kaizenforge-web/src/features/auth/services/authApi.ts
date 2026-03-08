import { httpClient } from '@/infra/http/httpClient'

import type { LoginRequest } from '@/features/auth/types/loginRequest'
import { loginResponseSchema } from '@/features/auth/schemas/loginResponseSchema'
import type { LoginResponse } from '@/features/auth/types/loginResponse'
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
}
