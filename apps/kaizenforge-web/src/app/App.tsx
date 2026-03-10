import { AuthBootstrapGate } from '@/features/auth/components/AuthBootstrapGate'
import { AppRouter } from '@/router/AppRouter'

export function App() {
  return (
    <AuthBootstrapGate>
      <AppRouter />
    </AuthBootstrapGate>
  )
}
