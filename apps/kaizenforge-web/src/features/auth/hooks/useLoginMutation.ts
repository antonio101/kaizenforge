import { useEffect, useRef } from 'react'
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

const inFlightError: HttpError = {
  status: null,
  code: 'canceled',
  message: 'Login request already in progress',
}

export function useLoginMutation(): UseLoginMutationResult {
  const abortControllerRef = useRef<AbortController | null>(null)

  const mutation = useMutation<LoginResponse, HttpError, LoginRequest>({
    mutationFn: async (payload) => {
      const nextAbortController = new AbortController()

      abortControllerRef.current = nextAbortController

      return authApi.login(payload, nextAbortController.signal)
    },
  })

  useEffect(() => {
    return () => {
      abortControllerRef.current?.abort()
    }
  }, [])

  async function handleLogin(payload: LoginRequest): Promise<LoginResponse> {
    if (mutation.isPending) {
      throw inFlightError
    }

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
