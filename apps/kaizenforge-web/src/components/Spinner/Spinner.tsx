import styles from './Spinner.module.scss'

export type SpinnerProps = {
  label?: string
}

export function Spinner({ label = 'Loading' }: SpinnerProps) {
  return (
    <span className={styles.Spinner} aria-live="polite" aria-label={label}>
      <span className={styles.track} />
    </span>
  )
}
