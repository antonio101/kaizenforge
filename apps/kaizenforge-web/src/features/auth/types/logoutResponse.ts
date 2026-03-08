import type { z } from 'zod'

import { logoutResponseSchema } from '@/features/auth/schemas/logoutResponseSchema'

export type LogoutResponse = z.infer<typeof logoutResponseSchema>
