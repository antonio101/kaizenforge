import type { z } from 'zod'

import { httpMessageErrorSchema } from '@/infra/http/schemas/httpMessageErrorSchema'

export type HttpErrorResponse = z.infer<typeof httpMessageErrorSchema>
