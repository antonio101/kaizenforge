import { Button } from '@/components/Button'
import { useAuthSession } from '@/features/auth/hooks/useAuthSession'
import { useLogoutMutation } from '@/features/auth/hooks/useLogoutMutation'

import styles from './AuthenticatedHeader.module.scss'

export function AuthenticatedHeader() {
  const { session } = useAuthSession()
  const { isPending, handleLogout } = useLogoutMutation()

  const userEmail = session?.user.email ?? 'Authenticated user'

  return (
    <header className={styles.AuthenticatedHeader}>
      <div className={styles.headerInner}>
        <div className={styles.brandBlock}>
          <span className={styles.brandEyebrow}>Kaizen Forge</span>
          <strong className={styles.brandTitle}>Application Shell</strong>
        </div>

        <div className={styles.sessionMeta}>
          <div className={styles.sessionStatus} aria-live="polite">
            Signed in
          </div>

          <div className={styles.userEmail} title={userEmail}>
            {userEmail}
          </div>
        </div>

        <div className={styles.actions}>
          <Button
            type="button"
            variant="secondary"
            isLoading={isPending}
            onClick={handleLogout}
          >
            Sign out
          </Button>
        </div>
      </div>
    </header>
  )
}
