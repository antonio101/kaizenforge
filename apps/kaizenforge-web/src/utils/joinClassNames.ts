export function joinClassNames(
  ...classNameParts: Array<string | false | null | undefined>
) {
  return classNameParts.filter(Boolean).join(' ')
}
