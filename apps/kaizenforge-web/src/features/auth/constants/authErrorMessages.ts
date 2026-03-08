import { authErrorKeys } from '@/features/auth/constants/authErrorKeys'

export const authErrorMessages = {
  [authErrorKeys.invalidCredentials]:
    'The credentials you entered are not valid.',
  [authErrorKeys.validation]:
    'Please review the highlighted fields.',
  [authErrorKeys.network]:
    'Unable to reach the server. Check your connection and try again.',
  [authErrorKeys.forbidden]:
    'You do not have permission to access the application.',
  [authErrorKeys.unavailable]:
    'The authentication service is temporarily unavailable.',
  [authErrorKeys.unexpected]:
    'Something went wrong while signing in. Please try again.',
} as const
