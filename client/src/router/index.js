import Vue from 'vue'
import store from '../store/index'
import Router from 'vue-router'
import AuthLayout from '../components/auth/AuthLayout'
import AppLayout from '../components/admin/AppLayout'

Vue.use(Router)

const EmptyParentComponent = {
  template: '<router-view></router-view>',
}

const demoRoutes = []

const router = new Router({
  mode: process.env.VUE_APP_ROUTER_MODE_HISTORY === 'true' ? 'history' : 'hash',
  routes: [
    ...demoRoutes,
    {
      path: '*',
      redirect: { name: 'dashboard' },
    },
    {
      path: '/auth',
      component: AuthLayout,
      children: [
        {
          name: 'login',
          path: 'login',
          component: () => import('../components/auth/login/Login.vue'),
        },
        {
          path: '',
          redirect: { name: 'login' },
        },
      ],
    },
    {
      path: '/404',
      component: EmptyParentComponent,
      children: [
        {
          name: 'not-found-advanced',
          path: 'not-found-advanced',
          component: () => import('../components/pages/404-pages/VaPageNotFoundSearch.vue'),
        },
        {
          name: 'not-found-simple',
          path: 'not-found-simple',
          component: () => import('../components/pages/404-pages/VaPageNotFoundSimple.vue'),
        },
        {
          name: 'not-found-custom',
          path: 'not-found-custom',
          component: () => import('../components/pages/404-pages/VaPageNotFoundCustom.vue'),
        },
        {
          name: 'not-found-large-text',
          path: '/pages/not-found-large-text',
          component: () => import('../components/pages/404-pages/VaPageNotFoundLargeText.vue'),
        },
      ],
    },
    {
      name: 'Admin',
      path: '/admin',
      component: AppLayout,
      children: [
        {
          name: 'dashboard',
          path: 'dashboard',
          component: () => import('../components/dashboard/Dashboard.vue'),
          default: true,
        },
        {
          name: 'parametres',
          path: 'parametres',
          component: EmptyParentComponent,
          children: [
            {
              name: 'niveaux',
              path: 'niveaux',
              component: () => import('../components/parametres/Niveaux.vue'),
            },
            {
              name: 'classes',
              path: 'classes',
              component: () => import('../components/parametres/Classes.vue'),
            },
            {
              name: 'matieres',
              path: 'matieres',
              component: () => import('../components/parametres/Matieres.vue'),
            },
            {
              name: 'professeurs',
              path: 'professeurs',
              component: () => import('../components/parametres/Professeurs.vue'),
            },
            {
              name: 'utilisateurs',
              path: 'utilisateurs',
              component: () => import('../components/parametres/Utilisateurs.vue'),
            },
          ],
        },
        {
          name: 'eleves',
          path: 'eleves',
          component: EmptyParentComponent,
          children: [
            {
              name: 'inscrireeleves',
              path: 'inscription',
              component: () => import('../components/eleves/Inscription.vue'),
            },
            {
              name: 'listeeleve',
              path: 'liste',
              component: () => import('../components/eleves/Liste.vue'),
            },
            {
              name: 'editionliste',
              path: 'editionliste',
              component: () => import('../components/eleves/EditionListe.vue'),
            },
            {
              name: 'ecolage',
              path: 'ecolage',
              component: () => import('../components/eleves/Ecolage.vue'),
            },
            {
              name: 'rapportecolage',
              path: 'rapportecolage',
              component: () => import('../components/eleves/RapportEcolage.vue'),
            },
          ],
        },
        {
          name: 'examens',
          path: 'examens',
          component: EmptyParentComponent,
          children: [
            {
              name: 'saisienotes',
              path: 'saisie',
              component: () => import('../components/examens/SaisieNotes.vue'),
            },
            {
              name: 'resultatexamens',
              path: 'resultats',
              component: () => import('../components/examens/ResultatExamens.vue'),
            },
          ],
        },
        {
          name: 'pages',
          path: 'pages',
          component: EmptyParentComponent,
          children: [
            {
              name: '404-pages',
              path: '404-pages',
              component: () => import('../components/pages/404-pages/404PagesPage'),
            },
          ],
        },
      ],
    },
  ],
})

// login guard
router.beforeEach((to, from, next) => {
  const user = store.getters.getUser
  console.log(to.name, user)
  if (user) {
    if (user.role === 'admin') {
      if (to.name === 'login') next({ name: 'dashboard' })
      else next()
    } else {
      const authorized = ['dashboard', 'saisienotes', 'resultatexamens']
      if (to.name === 'login') next({ name: 'dashboard' })
      else if (!authorized.includes(to.name)) next({ name: 'dashboard' })
      else next()
    }
  } else {
    if (to.name !== 'login') next({ name: 'login' })
    next()
  }
  next()
})

export default router
