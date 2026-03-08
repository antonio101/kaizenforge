import { z } from 'zod'

import { authenticatedUserSchema } from '@/features/auth/schemas/authenticatedUserSchema'

export const loginResponseSchema = z.object({
  accessToken: z.string().min(1),
  tokenType: z.literal('Bearer'),
  expiresAt: z.string().min(1),
  user: authenticatedUserSchema,
})
