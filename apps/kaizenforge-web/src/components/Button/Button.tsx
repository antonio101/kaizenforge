import type { ButtonHTMLAttributes } from 'react'
import { forwardRef } from 'react'

import { Spinner } from '@/components/Spinner'
import { joinClassNames } from '@/utils/joinClassNames'

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
  return (
    <button
      {...props}
      ref={ref}
      type={type}
      className={joinClassNames(
        styles.Button,
        styles[variant],
        fullWidth && styles.fullWidth,
        className
      )}
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
