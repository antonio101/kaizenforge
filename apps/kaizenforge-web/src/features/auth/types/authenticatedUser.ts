import type { z } from 'zod'

import { authenticatedUserSchema } from '@/features/auth/schemas/authenticatedUserSchema'

export type AuthenticatedUser = z.infer<typeof authenticatedUserSchema>
