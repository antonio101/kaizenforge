import type { InputHTMLAttributes } from 'react'
import { forwardRef } from 'react'

import { joinClassNames } from '@/utils/joinClassNames'

import styles from './Input.module.scss'

export type InputProps = InputHTMLAttributes<HTMLInputElement> & {
  hasError?: boolean
  fullWidth?: boolean
  hasTrailingAction?: boolean
}

export const Input = forwardRef<HTMLInputElement, InputProps>(function Input(
  {
    className,
    hasError = false,
    fullWidth = false,
    hasTrailingAction = false,
    ...props
  },
  ref
) {
  return (
    <input
      ref={ref}
      className={joinClassNames(
        styles.Input,
        hasError && styles.hasError,
        fullWidth && styles.fullWidth,
        hasTrailingAction && styles.hasTrailingAction,
        className
      )}
      {...props}
    />
  )
})
