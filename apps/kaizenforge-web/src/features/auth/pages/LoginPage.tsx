import { LoginForm } from '@/features/auth/components/LoginForm'
import { authErrorMessages } from '@/features/auth/constants/authErrorMessages'
import { useLoginForm } from '@/features/auth/hooks/useLoginForm'

import styles from './LoginPage.module.scss'

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
