<template>
  <b-container fluid>
    <b-row>
      <div style="margin-right: auto; margin-left: auto;">
        <General  v-if="user && user.role === 'admin'" @recharge="recharger()"/>
      </div>
    </b-row>
    <b-row>
      <div class="xs12 md12" style="margin-right: auto; margin-left: auto;">
        <va-card>
          <div class="row row-separated">
            <div class="flex xs6">
              <p class="display-2 mb-1 text--center" :style="{color: this.$themes.info, fontSize: '60px'}" v-if="stats">
                {{ stats.annee ? stats.annee.annee + ' - ' + (stats.annee.annee + 1) : 'Aucune annee ouverte' }}
              </p>
              <p class="text--center no-wrap mb-1">Annee scolaire</p>
            </div>
            <div class="flex xs6">
              <p class="display-2 mb-1 text--center" :style="{color: this.$themes.warning, fontSize: '60px'}" v-if="stats" >
                {{ stats.periode ? stats.periode.libelle : 'Aucune periode ouverte' }}
              </p>
              <p class="text--center mb-1">Periode</p>
            </div>
          </div>
        </va-card>
      </div>
    </b-row>
    <b-row>
      <div class="xl6 xs12" style="margin-right: auto; margin-left: auto;">
        <div class="row">
          <!--<div
            class="flex xs12 sm4"
            v-for="(info, idx) in infoTiles"
            :key="idx"
          >
            <va-card class="mb-4" :color="info.color">
              <p class="display-2 mb-0" style="color: white;">{{ info.value }}</p>
              <p>{{ info.text }}</p>
            </va-card>
          </div>-->

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="success">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.eleve }}</p>
              <p>Eleves</p>
            </va-card>
          </div>

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="info">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.fille }}</p>
              <p>Filles</p>
            </va-card>
          </div>

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="danger">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.garcon }}</p>
              <p>Garcons</p>
            </va-card>
          </div>

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="success">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.classe }}</p>
              <p>Classes</p>
            </va-card>
          </div>

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="info">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.profs }}</p>
              <p>Professeurs</p>
            </va-card>
          </div>

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="danger">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.eleveParti }}</p>
              <p>Eleves partis</p>
            </va-card>
          </div>

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="info">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.filleParti }}</p>
              <p>Filles parties</p>
            </va-card>
          </div>

          <div class="flex xs12 sm4">
            <va-card class="mb-4" color="success">
              <p class="display-2 mb-0" style="color: white;" v-if="stats">{{ stats.garconParti }}</p>
              <p>Garcons partis</p>
            </va-card>
          </div>

        </div>

        <div class="row"  v-if="user && user.role === 'admin'">
          <div class="flex md6">
            <va-card>
              <p class="display-2 mb-1" :style="{color: this.$themes.primary}" v-if="stats">{{ stats.ecolage }}<sub style="font-size: 15px;">FCFA</sub></p>
              <p class="no-wrap">Montant ecolage collecte</p>
            </va-card>
          </div>
          <div class="flex xs12 md6">
            <va-card>
              <div class="row row-separated">
                <div class="flex xs4">
                  <p class="display-2 mb-1 text--center" :style="{color: this.$themes.primary}" v-if="stats">{{ stats.nbPaiement }}</p>
                  <p class="text--center mb-1">Paiements</p>
                </div>
                <!-- <div class="flex xs4">
                  <p class="display-2 mb-1 text--center" :style="{color: this.$themes.info}">24</p>
                  <p class="text--center no-wrap mb-1">Paiements</p>
                </div>
                <div class="flex xs4">
                  <p class="display-2 mb-1 text--center" :style="{color: this.$themes.warning}">91</p>
                  <p class="text--center mb-1">dashboard.info.units</p>
                </div> -->
              </div>
            </va-card>
          </div>
        </div>
      </div>

    </b-row>
  </b-container>
</template>

<script>
import General from '@/components/general/General'
import stats from '@/services/api/stats'

export default {
  name: 'DashboardInfoBlock',
  components: { General },
  data () {
    return {
      infoTiles: [{
        color: 'success',
        value: '100',
        text: 'eleves',
        icon: '',
      }, {
        color: 'danger',
        value: '50',
        text: 'Garcon',
        icon: '',
      },
      {
        color: 'info',
        value: '50',
        text: 'Fille',
        icon: '',
      },
      {
        color: 'info',
        value: '50',
        text: 'Eleves partis',
        icon: '',
      },
      {
        color: 'info',
        value: '50',
        text: 'Garcons partis',
        icon: '',
      },
      {
        color: 'info',
        value: '50',
        text: 'Filles partis',
        icon: '',
      },
      {
        color: 'info',
        value: '50',
        text: 'Classes',
        icon: '',
      },
      {
        color: 'info',
        value: '50',
        text: 'Enseignants',
        icon: '',
      },

      ],
      stats: null,
    }
  },
  computed: {
    user () {
      const user = this.$store.getters.getUser
      return user
    },
  },
  mounted () {
    this.getStats()
  },
  methods: {
    getStats () {
      stats.get().then((res) => {
        this.stats = res.data
        console.log(this.stats)
      })
    },
    showModal () {
      this.modal = true
    },
    showPrevImage () {
      this.currImage = !this.currImage ? this.images.length - 1 : this.currImage - 1
    },
    showNextImage () {
      this.currImage = this.currImage === this.images.length - 1 ? 0 : this.currImage + 1
    },
    recharger () {
      this.getStats()
    },
  },
}
</script>

<style lang="scss">
  .row-separated {
    .flex + .flex {
      border-left: 1px solid #e3eaeb;
    }

    @include media-breakpoint-down(xs) {
      p:not(.display-2) {
        font-size: 0.875rem;
      }
    }
  }

  .dashboard {
    .va-card__header--over {
      @include media-breakpoint-up(md) {
        padding-top: 0 !important;
      }
    }

    .va-card__image {
      @include media-breakpoint-up(md) {
        padding-bottom: 0 !important;
      }
    }
  }
</style>
