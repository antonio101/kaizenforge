import type { z } from 'zod'

import { loginResponseSchema } from '@/features/auth/schemas/loginResponseSchema'

export type LoginResponse = z.infer<typeof loginResponseSchema>
