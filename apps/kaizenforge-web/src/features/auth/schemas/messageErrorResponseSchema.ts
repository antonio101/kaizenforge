import { z } from 'zod'

export const messageErrorResponseSchema = z.object({
  message: z.string().min(1),
})
