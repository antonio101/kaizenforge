import type { InputHTMLAttributes } from 'react'
import { forwardRef } from 'react'

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
  const inputClassNames = [styles.Input]

  if (hasError) {
    inputClassNames.push(styles.hasError)
  }

  if (fullWidth) {
    inputClassNames.push(styles.fullWidth)
  }

  if (hasTrailingAction) {
    inputClassNames.push(styles.hasTrailingAction)
  }

  if (className) {
    inputClassNames.push(className)
  }

  return <input ref={ref} className={inputClassNames.join(' ')} {...props} />
})
