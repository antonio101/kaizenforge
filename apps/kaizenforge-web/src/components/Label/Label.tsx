import type { LabelHTMLAttributes } from 'react'

import { joinClassNames } from '@/utils/joinClassNames'

import styles from './Label.module.scss'

export type LabelProps = LabelHTMLAttributes<HTMLLabelElement>

export function Label({ className, ...props }: LabelProps) {
  return <label className={joinClassNames(styles.Label, className)} {...props} />
}
