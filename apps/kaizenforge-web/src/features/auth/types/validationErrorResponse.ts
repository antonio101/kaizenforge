import type { z } from 'zod'

import {
  validationErrorDetailSchema,
  validationErrorResponseSchema,
} from '@/features/auth/schemas/validationErrorResponseSchema'

export type ValidationErrorDetail = z.infer<typeof validationErrorDetailSchema>

export type ValidationErrorResponse = z.infer<
  typeof validationErrorResponseSchema
>
