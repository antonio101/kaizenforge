import { LoginForm } from '@/features/auth/components/LoginForm'
import { authErrorKeys } from '@/features/auth/constants/authErrorKeys'
import { useLoginForm } from '@/features/auth/hooks/useLoginForm'

import styles from './LoginPage.module.scss'

const authErrorMessages = {
  [authErrorKeys.invalidCredentials]:
    'The credentials you entered are not valid.',
  [authErrorKeys.validation]:
    'Please review the highlighted fields.',
  [authErrorKeys.network]:
    'Unable to reach the server. Check your connection and try again.',
  [authErrorKeys.forbidden]:
    'You do not have permission to access the application.',
  [authErrorKeys.unavailable]:
    'The authentication service is temporarily unavailable.',
  [authErrorKeys.unexpected]:
    'Something went wrong while signing in. Please try again.',
} as const

export function LoginPage() {
  const { form, isSubmitting, submitErrorKey, handleSubmit } = useLoginForm()

  const submitErrorMessage = submitErrorKey
    ? authErrorMessages[submitErrorKey]
    : null

  return (
    <section className={styles.LoginPage} aria-labelledby="login-page-title">
      <div className={styles.content}>
        <span className={styles.eyebrow}>Authentication</span>

        <h1 id="login-page-title" className={styles.title}>
          Sign in to Kaizen Forge
        </h1>

        <p className={styles.description}>
          Access your weekly planning, daily execution flow and adherence tracking
          from a focused and structured workspace.
        </p>
      </div>

      <LoginForm
        form={form}
        isSubmitting={isSubmitting}
        submitErrorMessage={submitErrorMessage}
        onSubmit={handleSubmit}
      />
    </section>
  )
}
