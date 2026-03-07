import { Outlet } from 'react-router-dom'

import styles from './PublicLayout.module.scss'

export function PublicLayout() {
  return (
    <div className={styles.PublicLayout}>
      <main className={styles.content}>
        <section className={styles.hero} aria-labelledby="public-layout-title">
          <span className={styles.eyebrow}>Kaizen Forge</span>

          <h1 id="public-layout-title" className={styles.title}>
            Plan your week. Execute daily. Measure adherence.
          </h1>

          <p className={styles.description}>
            A focused product for habits, training planning and measurable consistency.
            This foundation is ready for authentication and the first business workflows.
          </p>
        </section>

        <section className={styles.panel}>
          <Outlet />
        </section>
      </main>
    </div>
  )
}
