import type { LabelHTMLAttributes } from 'react'

import styles from './Label.module.scss'

export type LabelProps = LabelHTMLAttributes<HTMLLabelElement>

export function Label({ className, ...props }: LabelProps) {
  const labelClassName = className
    ? `${styles.Label} ${className}`
    : styles.Label

  return <label className={labelClassName} {...props} />
}
