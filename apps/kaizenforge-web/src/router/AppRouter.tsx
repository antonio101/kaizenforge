import { BrowserRouter, Route, Routes } from 'react-router-dom'

import { LoginPage } from '@/features/auth/pages/LoginPage'
import { DashboardHomePage } from '@/features/dashboard/pages/DashboardHomePage'
import { AppLayout } from '@/layouts/AppLayout'
import { PublicLayout } from '@/layouts/PublicLayout'
import { PrivateRoute } from '@/router/guards/PrivateRoute'
import { PublicOnlyRoute } from '@/router/guards/PublicOnlyRoute'
import { RootRedirect } from '@/router/guards/RootRedirect'
import { NotFoundPage } from '@/router/pages/NotFoundPage'
import { paths } from '@/router/paths'

export function AppRouter() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path={paths.root} element={<RootRedirect />} />

        <Route element={<PublicOnlyRoute />}>
          <Route path={paths.login} element={<PublicLayout />}>
            <Route index element={<LoginPage />} />
          </Route>
        </Route>

        <Route element={<PrivateRoute />}>
          <Route path={paths.app} element={<AppLayout />}>
            <Route index element={<DashboardHomePage />} />
          </Route>
        </Route>

        <Route path="*" element={<NotFoundPage />} />
      </Routes>
    </BrowserRouter>
  )
}
