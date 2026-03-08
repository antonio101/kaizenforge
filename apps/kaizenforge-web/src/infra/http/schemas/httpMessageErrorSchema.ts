import { z } from 'zod'

export const httpMessageErrorSchema = z.object({
  message: z.string().min(1),
})
