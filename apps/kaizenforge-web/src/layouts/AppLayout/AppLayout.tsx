import { Outlet } from 'react-router-dom'

import styles from './AppLayout.module.scss'

export function AppLayout() {
  return (
    <div className={styles.AppLayout}>
      <header className={styles.header}>
        <div className={styles.headerInner}>
          <div className={styles.brandBlock}>
            <span className={styles.brandEyebrow}>Kaizen Forge</span>
            <strong className={styles.brandTitle}>Application Shell</strong>
          </div>

          <div className={styles.sessionStatus} aria-live="polite">
            Protected area
          </div>
        </div>
      </header>

      <main className={styles.main}>
        <div className={styles.content}>
          <Outlet />
        </div>
      </main>
    </div>
  )
}
