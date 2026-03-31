import { render, screen } from '@testing-library/react'
import { describe, expect, it } from 'vitest'

function TestComponent() {
  return <button type="button">Ready</button>
}

describe('testing setup', () => {
  it('renders with RTL and jest-dom matchers', () => {
    render(<TestComponent />)

    expect(screen.getByRole('button', { name: 'Ready' })).toBeInTheDocument()
  })
})