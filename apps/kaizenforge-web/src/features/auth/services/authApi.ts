import { httpClient } from '@/infra/http/httpClient'

import type { LoginRequest } from '@/features/auth/types/loginRequest'
import type { LoginResponse } from '@/features/auth/types/loginResponse'
import type { RequestConfig } from '@/infra/http/httpClient'

export const authApi = {
  login(payload: LoginRequest, signal?: AbortSignal) {
    const config: RequestConfig | undefined = signal ? { signal } : undefined

    return httpClient.post<LoginResponse, LoginRequest>(
      '/login_check',
      payload,
      config
    )
  },
}