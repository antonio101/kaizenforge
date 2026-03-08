import axios from 'axios'

import { httpMessageErrorSchema } from '@/infra/http/schemas/httpMessageErrorSchema'
import { httpValidationErrorSchema } from '@/infra/http/schemas/httpValidationErrorSchema'

import type {
  HttpError,
  HttpErrorCode,
  ValidationErrorDetail,
} from './httpErrors'

function getHttpErrorCode(status: number | null): HttpErrorCode {
  if (status === 400) {
    return 'bad_request'
  }

  if (status === 401) {
    return 'unauthorized'
  }

  if (status === 403) {
    return 'forbidden'
  }

  if (status === 404) {
    return 'not_found'
  }

  if (status === 409) {
    return 'conflict'
  }

  if (status === 422) {
    return 'validation_error'
  }

  if (status !== null && status >= 500) {
    return 'server_error'
  }

  return 'unknown_error'
}

function getValidationDetails(data: unknown): ValidationErrorDetail[] | undefined {
  const parsedValidation = httpValidationErrorSchema.safeParse(data)

  if (!parsedValidation.success) {
    return undefined
  }

  return parsedValidation.data.errors
}

function getResponseMessage(data: unknown, fallbackMessage: string) {
  const parsedValidation = httpValidationErrorSchema.safeParse(data)

  if (parsedValidation.success) {
    return parsedValidation.data.message
  }

  const parsedMessage = httpMessageErrorSchema.safeParse(data)

  if (parsedMessage.success) {
    return parsedMessage.data.message
  }

  return fallbackMessage
}

export function mapHttpError(error: unknown): HttpError {
  if (axios.isAxiosError(error)) {
    if (error.code === 'ERR_CANCELED') {
      return {
        status: null,
        code: 'canceled',
        message: error.message || 'Request canceled',
      }
    }

    const status = error.response?.status ?? null

    if (!error.response) {
      return {
        status: null,
        code: 'network_error',
        message: error.message || 'Network error',
      }
    }

    const details = getValidationDetails(error.response.data)

    return {
      status,
      code: getHttpErrorCode(status),
      message: getResponseMessage(
        error.response.data,
        error.message || 'Request failed'
      ),
      ...(details ? { details } : {}),
    }
  }

  if (error instanceof Error) {
    return {
      status: null,
      code: 'unknown_error',
      message: error.message,
    }
  }

  return {
    status: null,
    code: 'unknown_error',
    message: 'Unknown error',
  }
}
