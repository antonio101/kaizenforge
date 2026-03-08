import { z } from 'zod'

export const validationErrorDetailSchema = z.object({
  field: z.string().min(1),
  message: z.string().min(1),
})

export const validationErrorResponseSchema = z.object({
  message: z.string().min(1),
  errors: z.array(validationErrorDetailSchema),
})
