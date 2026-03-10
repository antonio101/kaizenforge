import { useMutation, useQueryClient } from '@tanstack/react-query'

import { authQueryKeys } from '@/features/auth/constants/authQueryKeys'
import { useAuthSession } from '@/features/auth/hooks/useAuthSession'
import { authApi } from '@/features/auth/services/authApi'
import type { LogoutResponse } from '@/features/auth/types/logoutResponse'
import type { HttpError } from '@/infra/http/httpErrors'

type UseLogoutMutationResult = {
  isPending: boolean
  handleLogout: () => Promise<void>
}

async function clearAuthenticatedSession(
  queryClient: ReturnType<typeof useQueryClient>,
  handleClearSession: () => void
) {
  await queryClient.cancelQueries({ queryKey: authQueryKeys.all })
  queryClient.removeQueries({ queryKey: authQueryKeys.all })
  handleClearSession()
}

export function useLogoutMutation(): UseLogoutMutationResult {
  const queryClient = useQueryClient()
  const { handleClearSession } = useAuthSession()

  const mutation = useMutation<LogoutResponse, HttpError>({
    mutationFn: () => authApi.logout(),
    retry: 0,
  })

  async function handleLogout() {
    try {
      await mutation.mutateAsync()
    } catch {
      // The user explicitly asked to sign out.
      // Local session cleanup still happens even if the remote request fails.
    } finally {
      await clearAuthenticatedSession(queryClient, handleClearSession)
    }
  }

  return {
    isPending: mutation.isPending,
    handleLogout,
  }
}
