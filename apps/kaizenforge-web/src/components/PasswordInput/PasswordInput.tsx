import type { MouseEvent } from 'react'
import { forwardRef, useId, useState } from 'react'

import { Input } from '@/components/Input'

import type { InputProps } from '../Input'

import styles from './PasswordInput.module.scss'

export type PasswordInputProps = Omit<InputProps, 'type' | 'hasTrailingAction'> & {
  toggleLabelShow?: string
  toggleLabelHide?: string
}

export const PasswordInput = forwardRef<HTMLInputElement, PasswordInputProps>(
  function PasswordInput(
    {
      className,
      disabled,
      toggleLabelShow = 'Show password',
      toggleLabelHide = 'Hide password',
      ...props
    },
    ref
  ) {
    const [isPasswordVisible, setIsPasswordVisible] = useState(false)
    const inputId = useId()

    function handleTogglePasswordVisibility(event: MouseEvent<HTMLButtonElement>) {
      event.preventDefault()
      setIsPasswordVisible((currentValue) => !currentValue)
    }

    const resolvedInputId = props.id ?? inputId
    const buttonLabel = isPasswordVisible ? toggleLabelHide : toggleLabelShow

    return (
      <div className={styles.PasswordInput}>
        <Input
          {...props}
          ref={ref}
          id={resolvedInputId}
          className={className}
          type={isPasswordVisible ? 'text' : 'password'}
          disabled={disabled}
          hasTrailingAction
        />

        <button
          type="button"
          className={styles.toggleButton}
          onClick={handleTogglePasswordVisibility}
          disabled={disabled}
          aria-label={buttonLabel}
          aria-controls={resolvedInputId}
          aria-pressed={isPasswordVisible}
        >
          {isPasswordVisible ? 'Hide' : 'Show'}
        </button>
      </div>
    )
  }
)
