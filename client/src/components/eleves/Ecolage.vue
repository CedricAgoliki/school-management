<template>
  <b-container>
    <b-row>
      <div class="p-3 border border-gray">
        <b-form @submit="save">
          <b-form-group label="Classe de l'eleve">
            <b-form-select v-model="form.classe" :options="classes" @change="loadEleves"></b-form-select>
          </b-form-group>
          <b-form-group label="Eleve">
            <b-form-select v-model="form.eleve_id" :options="eleves"></b-form-select>
          </b-form-group>
          <b-form-group label="Montant paye">
            <b-form-input v-model="form.montant"></b-form-input>
          </b-form-group>

          <b-form-group label="Date de paiement">
            <b-form-datepicker v-model="form.date"></b-form-datepicker>
          </b-form-group>

          <b-form-group label="Informations sur paiement (facultatif)">
            <b-form-textarea v-model="form.infos"></b-form-textarea>
          </b-form-group>
          <b-button type="submit" variant="success">valider</b-button>
          <b-button type="reset" variant="danger">Annuler</b-button>
        </b-form>
      </div>
    </b-row>
  </b-container>
</template>

<script>
import classes from '@/services/api/classes'
// import eleves from '@/services/api/eleves'
import paiements from '@/services/api/paiements'

export default {
  name: 'eleves',
  data () {
    return {
      classes: [],
      eleves: [],
      form: {
        classe: '',
        eleve_id: '',
        montant: '',
        date: null,
        infos: '',
      },
    }
  },
  mounted () {
    this.loadClasses()
  },
  methods: {
    loadClasses () {
      classes.get().then((res) => {
        this.classes = []
        for (const d of res.data) {
          this.classes.push({
            value: d.id,
            text: d.libelle,
          })
        }
      })
    },
    loadEleves () {
      classes.eleves(this.form.classe).then((res) => {
        this.eleves = []
        for (const d of res.data) {
          this.eleves.push({
            value: d.id,
            text: d.nom + ' ' + d.prenom,
          })
        }
      })
    },
    save (e) {
      e.preventDefault()
      const data = JSON.parse(JSON.stringify(this.form))
      console.log(data)
      paiements.save(data).then((res) => {
        console.log(res)
        this.$bvToast.toast('Paiements enregistre', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.form.classe = ''
        this.form.eleve_id = null
        this.form.montant = ''
        this.form.date = new Date()
      }).catch((err) => {
        this.$bvToast.toast(err.message, {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
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
