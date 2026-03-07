import { Navigate, Outlet } from 'react-router-dom'

import { useSession } from '@/app/providers/SessionProvider'
import { paths } from '@/router/paths'

export function PrivateRoute() {
  const { isAuthenticated, isSessionHydrated } = useSession()

  if (!isSessionHydrated) {
    return null
  }

  if (!isAuthenticated) {
    return <Navigate to={paths.login} replace />
  }

  return <Outlet />
}
