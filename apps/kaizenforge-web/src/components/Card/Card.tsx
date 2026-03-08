import type { HTMLAttributes } from 'react'

import styles from './Card.module.scss'

export type CardProps = HTMLAttributes<HTMLDivElement>

export function Card({ className, ...props }: CardProps) {
  const cardClassName = className ? `${styles.Card} ${className}` : styles.Card

  return <div className={cardClassName} {...props} />
}
