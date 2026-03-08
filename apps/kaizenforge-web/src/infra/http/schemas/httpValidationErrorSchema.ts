import { z } from 'zod'

export const httpValidationErrorDetailSchema = z.object({
  field: z.string().min(1),
  message: z.string().min(1),
})

export const httpValidationErrorSchema = z.object({
  message: z.string().min(1),
  errors: z.array(httpValidationErrorDetailSchema),
})
