import { describe, expect, it } from 'vitest'

import { normalizeLoginFormValues } from './normalizeLoginFormValues'

describe('normalizeLoginFormValues', () => {
  it('trims and lowercases email preserving password', () => {
    expect(
      normalizeLoginFormValues({
        email: '  John.DOE@Example.COM  ',
        password: 'Secret-123',
      })
    ).toEqual({
      email: 'john.doe@example.com',
      password: 'Secret-123',
    })
  })
})
