import type { z } from 'zod'

import { sessionSchema } from '@/features/auth/schemas/sessionSchema'

export type Session = z.infer<typeof sessionSchema>
