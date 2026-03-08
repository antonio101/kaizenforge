import type { z } from 'zod'

import { messageErrorResponseSchema } from '@/features/auth/schemas/messageErrorResponseSchema'

export type MessageErrorResponse = z.infer<typeof messageErrorResponseSchema>
