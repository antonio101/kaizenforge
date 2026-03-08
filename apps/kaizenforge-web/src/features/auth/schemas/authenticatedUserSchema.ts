import { z } from 'zod'

export const authenticatedUserSchema = z.object({
  id: z.string().min(1),
  email: z.string().email(),
  roles: z.array(z.string()),
})
