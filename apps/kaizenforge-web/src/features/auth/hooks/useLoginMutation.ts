import { useMutation } from '@tanstack/react-query'

import type { AuthErrorKey } from '@/features/auth/constants/authErrorKeys'
import { authApi } from '@/features/auth/services/authApi'
import type { LoginRequest } from '@/features/auth/types/loginRequest'
import type { LoginResponse } from '@/features/auth/types/loginResponse'
import { mapAuthErrorToErrorKey } from '@/features/auth/utils/mapAuthErrorToErrorKey'
import type { HttpError } from '@/infra/http/httpErrors'

type UseLoginMutationResult = {
  isPending: boolean
  hasError: boolean
  errorKey: AuthErrorKey | null
  handleLogin: (payload: LoginRequest) => Promise<LoginResponse>
  handleReset: () => void
}

export function useLoginMutation(): UseLoginMutationResult {
  const mutation = useMutation<LoginResponse, HttpError, LoginRequest>({
    mutationFn: (payload) => authApi.login(payload),
    retry: 0,
  })

  async function handleLogin(payload: LoginRequest): Promise<LoginResponse> {
    return mutation.mutateAsync(payload)
  }

  function handleReset() {
    mutation.reset()
  }

  const errorKey =
    mutation.error?.code === 'canceled'
      ? null
      : mutation.error
        ? mapAuthErrorToErrorKey(mutation.error)
        : null

  return {
    isPending: mutation.isPending,
    hasError: errorKey !== null,
    errorKey,
    handleLogin,
    handleReset,
  }
}