export const authErrorKeys = {
  invalidCredentials: 'auth.login.invalidCredentials',
  network: 'auth.login.network',
  forbidden: 'auth.login.forbidden',
  unavailable: 'auth.login.unavailable',
  unexpected: 'auth.login.unexpected',
} as const

export type AuthErrorKey =
  (typeof authErrorKeys)[keyof typeof authErrorKeys]
