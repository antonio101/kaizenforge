import type {
  AxiosError,
  AxiosResponse,
  InternalAxiosRequestConfig,
} from 'axios'
import { describe, expect, it } from 'vitest'

import { mapHttpError } from './mapHttpError'

type CreateAxiosErrorOptions = {
  code?: string
  message?: string
  status?: number
  data?: unknown
}

function createAxiosError({
  code,
  message = 'Request failed',
  status,
  data,
}: CreateAxiosErrorOptions): AxiosError {
  const config = {} as InternalAxiosRequestConfig

  const response: AxiosResponse<unknown> | undefined =
    status === undefined
      ? undefined
      : {
          data,
          status,
          statusText: '',
          headers: {},
          config,
        }

  return {
    name: 'AxiosError',
    message,
    isAxiosError: true,
    toJSON() {
      return {}
    },
    config,
    code,
    response,
  } as AxiosError
}

describe('mapHttpError', () => {
  it('maps canceled axios requests', () => {
    const error = createAxiosError({
      code: 'ERR_CANCELED',
      message: 'canceled by user',
    })

    expect(mapHttpError(error)).toEqual({
      status: null,
      code: 'canceled',
      message: 'canceled by user',
    })
  })

  it('maps network errors when the request has no response', () => {
    const error = createAxiosError({
      message: 'Network Error',
    })

    expect(mapHttpError(error)).toEqual({
      status: null,
      code: 'network_error',
      message: 'Network Error',
    })
  })

  it('maps validation errors and preserves field details', () => {
    const error = createAxiosError({
      status: 422,
      data: {
        message: 'Validation failed',
        errors: [
          { field: 'email', message: 'Invalid email address' },
          { field: 'password', message: 'Password is required' },
        ],
      },
    })

    expect(mapHttpError(error)).toEqual({
      status: 422,
      code: 'validation_error',
      message: 'Validation failed',
      details: [
        { field: 'email', message: 'Invalid email address' },
        { field: 'password', message: 'Password is required' },
      ],
    })
  })

  it('uses API message payload when present', () => {
    const error = createAxiosError({
      status: 401,
      data: { message: 'Invalid credentials' },
    })

    expect(mapHttpError(error)).toEqual({
      status: 401,
      code: 'unauthorized',
      message: 'Invalid credentials',
    })
  })

  it.each([
    [400, 'bad_request'],
    [403, 'forbidden'],
    [404, 'not_found'],
    [409, 'conflict'],
    [500, 'server_error'],
  ] as const)('maps status %s to %s', (status, expectedCode) => {
    const error = createAxiosError({ status })

    expect(mapHttpError(error)).toMatchObject({
      status,
      code: expectedCode,
      message: 'Request failed',
    })
  })

  it('maps native Error instances to unknown_error', () => {
    expect(mapHttpError(new Error('Boom'))).toEqual({
      status: null,
      code: 'unknown_error',
      message: 'Boom',
    })
  })

  it('maps unknown values to unknown_error', () => {
    expect(mapHttpError('boom')).toEqual({
      status: null,
      code: 'unknown_error',
      message: 'Unknown error',
    })
  })
})
