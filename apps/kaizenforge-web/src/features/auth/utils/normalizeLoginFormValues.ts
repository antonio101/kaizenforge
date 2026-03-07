import type { LoginFormValues } from '@/features/auth/types/loginFormValues'

export function normalizeLoginFormValues(
  values: LoginFormValues
): LoginFormValues {
  return {
    email: values.email.trim().toLowerCase(),
    password: values.password,
  }
}