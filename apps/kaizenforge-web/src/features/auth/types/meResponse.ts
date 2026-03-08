import type { z } from 'zod'

import { meResponseSchema } from '@/features/auth/schemas/meResponseSchema'

export type MeResponse = z.infer<typeof meResponseSchema>
