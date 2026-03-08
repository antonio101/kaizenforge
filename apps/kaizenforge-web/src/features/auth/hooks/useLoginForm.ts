import type { BaseSyntheticEvent } from 'react'
import { useEffect } from 'react'
import { zodResolver } from '@hookform/resolvers/zod'
import { useForm } from 'react-hook-form'

import type { AuthErrorKey } from '@/features/auth/constants/authErrorKeys'
import { authErrorKeys } from '@/features/auth/constants/authErrorKeys'
import { useAuthSession } from '@/features/auth/hooks/useAuthSession'
import { useLoginMutation } from '@/features/auth/hooks/useLoginMutation'
import { loginSchema } from '@/features/auth/schemas/loginSchema'
import type { LoginFormValues } from '@/features/auth/types/loginFormValues'
import { mapLoginResponseToSession } from '@/features/auth/utils/mapLoginResponseToSession'
import { normalizeLoginFormValues } from '@/features/auth/utils/normalizeLoginFormValues'
import type { HttpError, ValidationErrorDetail } from '@/infra/http/httpErrors'

type UseLoginFormResult = {
  form: ReturnType<typeof useForm<LoginFormValues>>
  isSubmitting: boolean
  submitErrorKey: AuthErrorKey | null
  handleSubmit: (event?: BaseSyntheticEvent) => Promise<void>
}

const defaultValues: LoginFormValues = {
  email: '',
  password: '',
}

function isHttpError(error: unknown): error is HttpError {
  return !!error && typeof error === 'object' && 'code' in error
}

function isLoginField(field: string): field is keyof LoginFormValues {
  return field === 'email' || field === 'password'
}

function applyValidationErrors(
  details: ValidationErrorDetail[] | undefined,
  setError: ReturnType<typeof useForm<LoginFormValues>>['setError']
) {
  if (!details) {
    return
  }

  for (const detail of details) {
    if (!isLoginField(detail.field)) {
      continue
    }

    setError(detail.field, {
      type: 'server',
      message: detail.message,
    })
  }
}

export function useLoginForm(): UseLoginFormResult {
  const { handleSetSession } = useAuthSession()
  const { handleLogin, isPending, hasError, errorKey, handleReset } =
    useLoginMutation()

  const form = useForm<LoginFormValues>({
    defaultValues,
    resolver: zodResolver(loginSchema),
    mode: 'onBlur',
  })

  useEffect(() => {
    if (!hasError) {
      return
    }

    const subscription = form.watch(() => {
      handleReset()
    })

    return () => {
      subscription.unsubscribe()
    }
  }, [form, hasError, handleReset])

  const handleSubmit = form.handleSubmit(async (values) => {
    form.clearErrors()

    const normalizedValues = normalizeLoginFormValues(values)

    try {
      const response = await handleLogin(normalizedValues)
      const nextSession = mapLoginResponseToSession(response)

      handleSetSession(nextSession)
    } catch (error) {
      if (!isHttpError(error)) {
        throw error
      }

      if (error.code === 'canceled') {
        return
      }

      if (error.code === 'validation_error') {
        applyValidationErrors(error.details, form.setError)
        return
      }

      throw error
    }
  })

  return {
    form,
    isSubmitting: form.formState.isSubmitting || isPending,
    submitErrorKey:
      errorKey === authErrorKeys.validation ? null : errorKey,
    handleSubmit,
  }
}
