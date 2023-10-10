<template>
  <b-container>
    <b-row>
      <div class="p-3 border border-gray">
        <b-button v-b-modal.ouvrir-annee>Ouverture d'annee scolaire</b-button>
        <b-button v-b-modal.fermer-annee>Cloture d'annee scolaire</b-button>
        <b-button v-b-modal.ouvrir-periode @click="loadPeriodes">Ouverture d'un trimestre</b-button>
        <b-button v-b-modal.fermer-periode>Cloture de trimestre</b-button>
      </div>
    </b-row>

    <b-modal id="ouvrir-annee" size="lg" hide-footer>
      <div>
        <h3>Ouverture d'annee scolaire</h3>
        <b-form>
          <b-form-group>
            <b-form-input type="number" v-model="formAnnee.annee"></b-form-input>
          </b-form-group>
          <b-button variant="success" @click="ouvrirAnnee">Ouvrir</b-button>
        </b-form>

      </div>
    </b-modal>

    <b-modal id="fermer-annee" size="lg" hide-footer>
      <div>
        <h3>Fermeture d'annee scolaire</h3>
        <div class="text-center">
          <b-button variant="danger" @click="fermerAnnee">Cloturer l'annee en cours</b-button>
        </div>
      </div>
    </b-modal>

    <b-modal id="ouvrir-periode" size="lg" hide-footer>
      <div>
        <h3>Ouverture de periode</h3>
        <b-form>
          <b-form-group>
            <b-form-select type="number" v-model="formPeriode.periode" :options="formPeriode.options"></b-form-select>
          </b-form-group>
          <b-button variant="success" @click="ouvrirPeriode">Ouvrir</b-button>
        </b-form>

      </div>
    </b-modal>

    <b-modal id="fermer-periode" size="lg" hide-footer>
      <div>
        <h3>Fermeture de periode</h3>
        <div class="text-center">
          <b-button variant="danger" @click="fermerPeriode">Cloturer la periode en cours</b-button>
        </div>
      </div>
    </b-modal>

  </b-container>
</template>

<script>
import annees from '@/services/api/annees'
import periodes from '@/services/api/periodes'

export default {
  name: 'General',
  data () {
    return {
      formAnnee: {
        annee: new Date().getFullYear(),
      },
      formPeriode: {
        options: [
        ],
        periode: null,
      },
    }
  },
  mounted () {
  },
  methods: {
    ouvrirAnnee () {
      const data = JSON.parse(JSON.stringify(this.formAnnee))
      annees.ouverture(data).then((res) => {
        this.$bvToast.toast('Annee ouvert', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.$emit('recharge')
        this.$bvModal.hide('ouvrir-annee')
      }).catch((err) => {
        this.$bvToast.toast(err.message, {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    fermerAnnee () {
      annees.fermeture().then((res) => {
        this.$bvToast.toast('Annee cloturee', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.$emit('recharge')
        this.$bvModal.hide('fermer-annee')
      }).catch((err) => {
        console.log(err.message)
        this.$bvToast.toast('erreur lors de la cloture', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    ouvrirPeriode () {
      const id = this.formPeriode.periode
      periodes.ouverture(id).then((res) => {
        this.formPeriode.periode = null
        this.$bvToast.toast('Periode ouverte', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.$emit('recharge')
        this.$bvModal.hide('ouvrir-periode')
      }).catch((err) => {
        this.$bvToast.toast(err.message, {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    fermerPeriode () {
      periodes.fermeture().then((res) => {
        this.$bvToast.toast('Periode cloturee', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.$emit('recharge')
        this.$bvModal.hide('fermer-periode')
      }).catch((err) => {
        console.log(err.message)
        this.$bvToast.toast('erreur lors de la cloture', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    loadPeriodes () {
      periodes.get().then((res) => {
        this.formPeriode.options = []
        for (const p of res.data) {
          this.formPeriode.options.push({
            value: p.id,
            text: p.libelle,
          })
        }
      })
    },
  },
}
</script>

<style lang="scss">
.chart-widget {
  .va-card__body {
    height: 550px;
  }
}
</style>
