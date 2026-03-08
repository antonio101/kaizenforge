import axios from 'axios'

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
  if (!data || typeof data !== 'object' || !('errors' in data)) {
    return undefined
  }

  const rawErrors = (data as { errors?: unknown }).errors

  if (!Array.isArray(rawErrors)) {
    return undefined
  }

  const details = rawErrors.filter(
    (item): item is ValidationErrorDetail =>
      !!item &&
      typeof item === 'object' &&
      'field' in item &&
      'message' in item &&
      typeof item.field === 'string' &&
      typeof item.message === 'string'
  )

  return details.length > 0 ? details : undefined
}

function getResponseMessage(data: unknown, fallbackMessage: string) {
  if (!data || typeof data !== 'object' || !('message' in data)) {
    return fallbackMessage
  }

  return typeof data.message === 'string' ? data.message : fallbackMessage
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

    return {
      status,
      code: getHttpErrorCode(status),
      message: getResponseMessage(error.response.data, error.message || 'Request failed'),
      details: getValidationDetails(error.response.data),
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
