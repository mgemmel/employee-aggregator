import OverView from '../pages/OverviewPage.vue'
import EmployeePage from '../pages/EmployeePage.vue'
import MainLayout from '../layouts/MainLayout.vue'

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      {name: 'overview', path: '/', component: OverView},
      {name: 'employees', path: '/employees', component: EmployeePage}
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
