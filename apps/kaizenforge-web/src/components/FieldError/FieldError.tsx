import type { ReactNode } from 'react'

import styles from './FieldError.module.scss'

export type FieldErrorProps = {
  id?: string
  children?: ReactNode
}

export function FieldError({ id, children }: FieldErrorProps) {
  if (!children) {
    return null
  }

  return (
    <p id={id} className={styles.FieldError} role="alert">
      {children}
    </p>
  )
}
