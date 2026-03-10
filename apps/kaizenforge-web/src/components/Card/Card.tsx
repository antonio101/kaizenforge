import type { HTMLAttributes } from 'react'

import { joinClassNames } from '@/utils/joinClassNames'

import styles from './Card.module.scss'

export type CardProps = HTMLAttributes<HTMLDivElement>

export function Card({ className, ...props }: CardProps) {
  return <div className={joinClassNames(styles.Card, className)} {...props} />
}
