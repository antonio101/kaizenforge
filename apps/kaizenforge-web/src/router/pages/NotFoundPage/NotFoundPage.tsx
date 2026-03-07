import { Link } from 'react-router-dom'

import { paths } from '@/router/paths'

import styles from './NotFoundPage.module.scss'

export function NotFoundPage() {
  return (
    <main className={styles.NotFoundPage}>
      <section className={styles.card} aria-labelledby="not-found-page-title">
        <p className={styles.code}>404</p>

        <h1 id="not-found-page-title" className={styles.title}>
          Page not found
        </h1>

        <p className={styles.description}>
          The route you requested does not exist in the current application.
        </p>

        <Link className={styles.link} to={paths.root}>
          Go back to the app entry point
        </Link>
      </section>
    </main>
  )
}
