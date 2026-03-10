import type { PropsWithChildren } from 'react'

import { Spinner } from '@/components/Spinner'
import { useAuthBootstrap } from '@/features/auth/hooks/useAuthBootstrap'

export function AuthBootstrapGate({ children }: PropsWithChildren) {
  const { isAuthReady } = useAuthBootstrap()

  if (!isAuthReady) {
    return (
      <main className="appShell" aria-labelledby="app-bootstrap-title">
        <section className="appShellCard">
          <span className="appShellEyebrow">Kaizen Forge</span>

          <h1 id="app-bootstrap-title" className="appShellTitle">
            Preparing your workspace
          </h1>

          <p className="appShellDescription">
            Validating the current session and restoring the authenticated
            experience.
          </p>

          <div className="appShellSpinner">
            <Spinner label="Loading application" />
          </div>
        </section>
      </main>
    )
  }

  return children
}
