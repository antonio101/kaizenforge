import type { AxiosRequestConfig, GenericAbortSignal } from 'axios'
import axios from 'axios'

import { appConfig } from '@/config/appConfig'
import { authSessionStorage } from '@/infra/storage/authSessionStorage'

import { mapHttpError } from './mapHttpError'

export type RequestParams = Record<
  string,
  string | number | boolean | undefined
>

export type RequestConfig = {
  signal?: GenericAbortSignal
  params?: RequestParams
  headers?: Record<string, string>
}

type RequestMethod = 'get' | 'post' | 'put' | 'patch' | 'delete'

type RequestOptions<TBody = unknown> = {
  method: RequestMethod
  url: string
  body?: TBody
  config?: RequestConfig
}

const http = axios.create({
  baseURL: appConfig.apiBaseUrl,
  timeout: appConfig.apiTimeoutMs,
  headers: {
    Accept: 'application/json',
  },
})

http.interceptors.request.use((config) => {
  const session = authSessionStorage.getSession()

  if (session?.accessToken) {
    config.headers = config.headers ?? {}
    config.headers.Authorization = `Bearer ${session.accessToken}`
  }

  return config
})

function buildHeaders(
  body: unknown,
  customHeaders?: Record<string, string>
): Record<string, string> {
  const headers = {
    ...customHeaders,
  }

  const hasJsonBody =
    body !== undefined &&
    body !== null &&
    !(body instanceof FormData) &&
    !headers['Content-Type']

  if (hasJsonBody) {
    headers['Content-Type'] = 'application/json'
  }

  return headers
}

function withConfig(config?: RequestConfig) {
  return config ? { config } : {}
}

function withBody<TBody>(body?: TBody) {
  return body !== undefined ? { body } : {}
}

async function request<TResponse, TBody = unknown>({
  method,
  url,
  body,
  config,
}: RequestOptions<TBody>): Promise<TResponse> {
  try {
    const requestConfig: AxiosRequestConfig = {
      method,
      url,
      headers: buildHeaders(body, config?.headers),
    }

    if (body !== undefined) {
      requestConfig.data = body
    }

    if (config?.signal) {
      requestConfig.signal = config.signal
    }

    if (config?.params) {
      requestConfig.params = config.params
    }

    const response = await http.request<TResponse>(requestConfig)

    return response.data
  } catch (error) {
    throw mapHttpError(error)
  }
}

export const httpClient = {
  get<TResponse>(url: string, config?: RequestConfig) {
    return request<TResponse>({
      method: 'get',
      url,
      ...withConfig(config),
    })
  },

  post<TResponse, TBody = unknown>(
    url: string,
    body?: TBody,
    config?: RequestConfig
  ) {
    return request<TResponse, TBody>({
      method: 'post',
      url,
      ...withBody(body),
      ...withConfig(config),
    })
  },

  put<TResponse, TBody = unknown>(
    url: string,
    body?: TBody,
    config?: RequestConfig
  ) {
    return request<TResponse, TBody>({
      method: 'put',
      url,
      ...withBody(body),
      ...withConfig(config),
    })
  },

  patch<TResponse, TBody = unknown>(
    url: string,
    body?: TBody,
    config?: RequestConfig
  ) {
    return request<TResponse, TBody>({
      method: 'patch',
      url,
      ...withBody(body),
      ...withConfig(config),
    })
  },

  delete<TResponse>(url: string, config?: RequestConfig) {
    return request<TResponse>({
      method: 'delete',
      url,
      ...withConfig(config),
    })
  },
}