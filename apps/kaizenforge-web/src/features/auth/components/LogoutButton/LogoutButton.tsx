import { Button } from '@/components/Button'

export type LogoutButtonProps = {
  isLoading?: boolean
  onLogout: () => Promise<void>
}

export function LogoutButton({
  isLoading = false,
  onLogout,
}: LogoutButtonProps) {
  return (
    <Button
      type="button"
      variant="secondary"
      isLoading={isLoading}
      onClick={onLogout}
    >
      Sign out
    </Button>
  )
}
