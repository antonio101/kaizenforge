export type HttpErrorCode =
  | 'bad_request'
  | 'unauthorized'
  | 'forbidden'
  | 'not_found'
  | 'conflict'
  | 'validation_error'
  | 'server_error'
  | 'network_error'
  | 'canceled'
  | 'unknown_error'

export type ValidationErrorDetail = {
  field: string
  message: string
}

export type HttpError = {
  status: number | null
  code: HttpErrorCode
  message: string
  details?: ValidationErrorDetail[]
}
