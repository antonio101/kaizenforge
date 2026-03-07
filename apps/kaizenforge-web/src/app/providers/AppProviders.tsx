import type { PropsWithChildren } from 'react'

import { QueryProvider } from '@/app/providers/QueryProvider'
import { SessionProvider } from '@/app/providers/SessionProvider'

export function AppProviders({ children }: PropsWithChildren) {
  return (
    <QueryProvider>
      <SessionProvider>{children}</SessionProvider>
    </QueryProvider>
  )
}
