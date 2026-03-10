import { Outlet } from 'react-router-dom'

import { AuthenticatedHeader } from '@/layouts/AppLayout/components/AuthenticatedHeader'

import styles from './AppLayout.module.scss'

export function AppLayout() {
  return (
    <div className={styles.AppLayout}>
      <AuthenticatedHeader />

      <main className={styles.main}>
        <div className={styles.content}>
          <Outlet />
        </div>
      </main>
    </div>
  )
}
