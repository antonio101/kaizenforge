import { Navigate } from 'react-router-dom'

import { useSession } from '@/app/providers/SessionProvider'
import { paths } from '@/router/paths'

export function RootRedirect() {
  const { isAuthenticated, isSessionHydrated } = useSession()

  if (!isSessionHydrated) {
    return null
  }

  return <Navigate to={isAuthenticated ? paths.app : paths.login} replace />
}
