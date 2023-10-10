const routes = [
  {
    name: 'dashboard',
    displayName: 'Tableau de bord',
    meta: {
      iconClass: 'vuestic-iconset vuestic-iconset-dashboard',
    },
  },
  {
    name: 'eleves',
    displayName: 'Eleves',
    meta: {
      iconClass: 'vuestic-iconset vuestic-iconset-statistics',
    },
    disabled: true,
    children: [
      {
        name: 'inscrireeleves',
        displayName: 'Inscrire un eleve',
      },
      {
        name: 'listeeleve',
        displayName: 'Liste des eleves',
      },
      {
        name: 'editionliste',
        displayName: 'Edition de liste par classe',
      },
    ],
  },
  {
    name: 'eleves',
    displayName: 'ecolage',
    meta: {
      iconClass: 'vuestic-iconset vuestic-iconset-statistics',
    },
    disabled: true,
    children: [
      {
        name: 'ecolage',
        displayName: 'Paiement d\'ecolage',
      },
      {
        name: 'rapportecolage',
        displayName: 'Rapport sur ecolage',
      },
    ],
  },
  {
    name: 'examens',
    displayName: 'Examens',
    meta: {
      iconClass: 'vuestic-iconset vuestic-iconset-forms',
    },
    disabled: true,
    children: [
      {
        name: 'saisienotes',
        displayName: 'Saisie des notes',
      },
      {
        name: 'resultatexamens',
        displayName: 'Resultat des examens',
      },
    ],
  },
  {
    name: 'parametres',
    displayName: 'Parametres',
    meta: {
      iconClass: 'vuestic-iconset vuestic-iconset-tables',
    },
    children: [
      {
        name: 'niveaux',
        displayName: 'Niveaux',
      },
      {
        name: 'classes',
        displayName: 'Classes',
      },
      {
        name: 'matieres',
        displayName: 'Matieres',
      },
      {
        name: 'professeurs',
        displayName: 'Professeurs',
      },
      {
        name: 'utilisateurs',
        displayName: 'Utilisateurs',
      },
    ],
  },
]

const saisieroutes = [
  {
    name: 'dashboard',
    displayName: 'Tableau de bord',
    meta: {
      iconClass: 'vuestic-iconset vuestic-iconset-dashboard',
    },
  },
  {
    name: 'examens',
    displayName: 'Examens',
    meta: {
	  iconClass: 'vuestic-iconset vuestic-iconset-forms',
    },
    disabled: true,
    children: [
	  {
	    name: 'saisienotes',
	    displayName: 'Saisie des notes',
	  },
	  {
	    name: 'resultatexamens',
	    displayName: 'Resultat des examens',
	  },
    ],
  },
]

export const navigationRoutes = {
  root: {
    name: '/',
    displayName: 'navigationRoutes.home',
  },
  routes: routes,
  saisieroutes: saisieroutes,
}
