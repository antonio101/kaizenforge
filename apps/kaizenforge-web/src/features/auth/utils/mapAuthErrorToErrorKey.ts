import type { HttpError } from '@/infra/http/httpErrors'

import { authErrorKeys } from '@/features/auth/constants/authErrorKeys'

export function mapAuthErrorToErrorKey(error: HttpError) {
  if (error.code === 'unauthorized') {
    return authErrorKeys.invalidCredentials
  }

  if (error.code === 'validation_error') {
    return authErrorKeys.validation
  }

  if (error.code === 'forbidden') {
    return authErrorKeys.forbidden
  }

  if (error.code === 'network_error') {
    return authErrorKeys.network
  }

  if (error.code === 'server_error') {
    return authErrorKeys.unavailable
  }

  return authErrorKeys.unexpected
}
