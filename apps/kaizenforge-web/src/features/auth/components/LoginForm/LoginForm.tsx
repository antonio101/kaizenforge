import type { BaseSyntheticEvent } from 'react'
import type { UseFormReturn } from 'react-hook-form'

import { Button } from '@/components/Button'
import { FieldError } from '@/components/FieldError'
import { Input } from '@/components/Input'
import { Label } from '@/components/Label'
import { PasswordInput } from '@/components/PasswordInput'
import type { LoginFormValues } from '@/features/auth/types/loginFormValues'

import styles from './LoginForm.module.scss'

export type LoginFormProps = {
  form: UseFormReturn<LoginFormValues>
  isSubmitting?: boolean
  submitErrorMessage?: string | null
  onSubmit: (event?: BaseSyntheticEvent) => Promise<void>
}

export function LoginForm({
  form,
  isSubmitting = false,
  submitErrorMessage,
  onSubmit,
}: LoginFormProps) {
  const emailErrorMessage = form.formState.errors.email?.message
  const passwordErrorMessage = form.formState.errors.password?.message

  const emailErrorId = emailErrorMessage ? 'login-email-error' : undefined
  const passwordErrorId = passwordErrorMessage ? 'login-password-error' : undefined
  const submitErrorId = submitErrorMessage ? 'login-submit-error' : undefined

  return (
    <form className={styles.LoginForm} onSubmit={onSubmit} noValidate>
      <div className={styles.fieldGroup}>
        <Label htmlFor="login-email">Email</Label>

        <Input
          id="login-email"
          type="email"
          autoComplete="email"
          placeholder="name@example.com"
          hasError={Boolean(emailErrorMessage)}
          aria-invalid={Boolean(emailErrorMessage)}
          aria-describedby={emailErrorId}
          fullWidth
          {...form.register('email')}
        />

        <FieldError id={emailErrorId}>{emailErrorMessage}</FieldError>
      </div>

      <div className={styles.fieldGroup}>
        <Label htmlFor="login-password">Password</Label>

        <PasswordInput
          id="login-password"
          autoComplete="current-password"
          placeholder="Enter your password"
          hasError={Boolean(passwordErrorMessage)}
          aria-invalid={Boolean(passwordErrorMessage)}
          aria-describedby={passwordErrorId}
          fullWidth
          {...form.register('password')}
        />

        <FieldError id={passwordErrorId}>{passwordErrorMessage}</FieldError>
      </div>

      <FieldError id={submitErrorId}>{submitErrorMessage}</FieldError>

      <Button type="submit" isLoading={isSubmitting} fullWidth>
        Sign in
      </Button>
    </form>
  )
}
