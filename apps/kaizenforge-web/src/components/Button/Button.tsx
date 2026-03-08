import type { ButtonHTMLAttributes } from 'react'
import { forwardRef } from 'react'

import { Spinner } from '@/components/Spinner'

import styles from './Button.module.scss'

export type ButtonVariant = 'primary' | 'secondary' | 'ghost'

export type ButtonProps = ButtonHTMLAttributes<HTMLButtonElement> & {
  variant?: ButtonVariant
  isLoading?: boolean
  fullWidth?: boolean
}

export const Button = forwardRef<HTMLButtonElement, ButtonProps>(function Button(
  {
    children,
    className,
    disabled,
    variant = 'primary',
    isLoading = false,
    fullWidth = false,
    type = 'button',
    ...props
  },
  ref
) {
  const buttonClassNames = [styles.Button, styles[variant]]

  if (fullWidth) {
    buttonClassNames.push(styles.fullWidth)
  }

  if (className) {
    buttonClassNames.push(className)
  }

  return (
    <button
      {...props}
      ref={ref}
      type={type}
      className={buttonClassNames.join(' ')}
      disabled={disabled || isLoading}
    >
      <span className={styles.content} data-loading={isLoading}>
        {children}
      </span>

      {isLoading ? (
        <span className={styles.spinner}>
          <Spinner label="Button loading" />
        </span>
      ) : null}
    </button>
  )
})
