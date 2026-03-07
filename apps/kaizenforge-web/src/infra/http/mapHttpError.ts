import axios from 'axios'

import type { HttpError, HttpErrorCode } from './httpErrors'

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
      message: error.message || 'Request failed',
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