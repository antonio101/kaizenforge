import type { z } from 'zod'

import {
  httpValidationErrorDetailSchema,
  httpValidationErrorSchema,
} from '@/infra/http/schemas/httpValidationErrorSchema'

export type HttpValidationErrorDetail = z.infer<
  typeof httpValidationErrorDetailSchema
>

export type HttpValidationErrorResponse = z.infer<
  typeof httpValidationErrorSchema
>
