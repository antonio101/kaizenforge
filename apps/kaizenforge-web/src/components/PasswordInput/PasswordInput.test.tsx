import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { describe, expect, it } from 'vitest'

import { PasswordInput } from './PasswordInput'

describe('PasswordInput', () => {
  it('toggles password visibility while keeping an accessible state contract', async () => {
    const user = userEvent.setup()

    render(<PasswordInput aria-label="Password" />)

    const input = screen.getByLabelText('Password')
    const toggleButton = screen.getByRole('button', {
      name: 'Show password',
    })

    expect(input).toHaveAttribute('type', 'password')
    expect(input).toHaveAttribute('id')
    expect(toggleButton).toHaveAttribute('aria-pressed', 'false')
    expect(toggleButton).toHaveAttribute('aria-controls', input.getAttribute('id'))

    await user.click(toggleButton)

    expect(input).toHaveAttribute('type', 'text')
    expect(toggleButton).toHaveAccessibleName('Hide password')
    expect(toggleButton).toHaveAttribute('aria-pressed', 'true')
    expect(toggleButton).toHaveAttribute('aria-controls', input.getAttribute('id'))

    await user.click(toggleButton)

    expect(input).toHaveAttribute('type', 'password')
    expect(toggleButton).toHaveAccessibleName('Show password')
    expect(toggleButton).toHaveAttribute('aria-pressed', 'false')
  })

  it('links the toggle button to the provided input id', () => {
    render(<PasswordInput id="login-password" aria-label="Password" />)

    const input = screen.getByLabelText('Password')
    const toggleButton = screen.getByRole('button', {
      name: 'Show password',
    })

    expect(input).toHaveAttribute('id', 'login-password')
    expect(toggleButton).toHaveAttribute('aria-controls', 'login-password')
  })

  it('does not toggle visibility when disabled', async () => {
    const user = userEvent.setup()

    render(<PasswordInput aria-label="Password" disabled />)

    const input = screen.getByLabelText('Password')
    const toggleButton = screen.getByRole('button', {
      name: 'Show password',
    })

    expect(toggleButton).toBeDisabled()
    expect(input).toBeDisabled()
    expect(input).toHaveAttribute('type', 'password')
    expect(toggleButton).toHaveAttribute('aria-pressed', 'false')

    await user.click(toggleButton)

    expect(input).toHaveAttribute('type', 'password')
    expect(toggleButton).toHaveAccessibleName('Show password')
    expect(toggleButton).toHaveAttribute('aria-pressed', 'false')
  })
})