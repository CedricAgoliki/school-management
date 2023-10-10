<template>
  <b-row>
    <div class="p-5">
      <b-button variant="success" v-b-modal.gerer-profs>Ajouter professeur</b-button>
      <b-table striped hover :fields="fields" :items="items">
        <template #cell(options)="data">
          <b-button variant="primary" class="mr-2" @click="onModifyButton(data.item)" v-b-modal.gerer-profs>modifier</b-button>
          <b-button variant="danger" @click="destroy(data.item.id)">delete</b-button>
        </template>
      </b-table>

      <b-modal id="gerer-profs" size="lg" hide-footer>
        <div>
          <b-form @submit="save">
            <b-form-group label="Nom">
              <b-form-input v-model="form.nom"></b-form-input>
            </b-form-group>
            <b-form-group label="Prenoms">
              <b-form-input v-model="form.prenom"></b-form-input>
            </b-form-group>

            <b-form-group label="sexe">
              <b-form-select v-model="form.sexe" :options="sexes"></b-form-select>
            </b-form-group>

            <b-form-group label="Grade">
              <b-form-input v-model="form.grade"></b-form-input>
            </b-form-group>

            <b-form-group label="Fonction">
              <b-form-input v-model="form.fonction"></b-form-input>
            </b-form-group>

            <b-form-group label="Contacts">
              <b-form-textarea v-model="form.contact"></b-form-textarea>
            </b-form-group>

            <b-form-group label="Diplome">
              <b-form-input v-model="form.diplome"></b-form-input>
            </b-form-group>

            <b-form-group label="Prise de service">
              <b-form-datepicker v-model="form.priseService"></b-form-datepicker>
            </b-form-group>

            <b-form-group label="Affectation DRE">
              <b-form-datepicker v-model="form.dre"></b-form-datepicker>
            </b-form-group>

            <b-form-group label="Affectation IEPP">
              <b-form-datepicker v-model="form.iepp"></b-form-datepicker>
            </b-form-group>

            <b-form-group label="Affectation poste">
              <b-form-datepicker v-model="form.affectationPoste"></b-form-datepicker>
            </b-form-group>

            <b-form-group label="retraite">
              <b-form-datepicker v-model="form.retraite"></b-form-datepicker>
            </b-form-group>

            <b-button type="submit" variant="success">Valider</b-button>
            <b-button type="reset" variant="danger">Annuler</b-button>
          </b-form>
        </div>
      </b-modal>
    </div>
  </b-row>
</template>

<script>

import professeurs from '@/services/api/professeurs'

export default {
  name: 'niveaux',
  data () {
    return {
      fields: ['nom', 'prenom', 'contact', 'options'],
      items: [],
      sexes: [{ value: 'F', text: 'Feminin' }, { value: 'M', text: 'Masculin' }],
      form: {
        nom: '',
        prenom: '',
        contact: '',
        sexe: '',
        grade: '',
        fonction: '',
        diplome: '',
        priseService: '',
        dre: '',
        iepp: '',
        affectationPoste: '',
        retraite: '',
      },
      toModify: null,
    }
  },
  mounted () {
    this.get()
  },
  methods: {
    get () {
      this.toModify = null
      professeurs.get().then((res) => {
        this.items = []
        for (const d of res.data) {
          this.items.push(d)
        }
      })
    },
    save (e) {
      e.preventDefault()
      this.$bvModal.hide('gerer-profs')
      console.log(e)
      const data = JSON.parse(JSON.stringify(this.form))
      if (this.toModify) this.update(data)
      else this.add(data)
    },
    add (data) {
      professeurs.save(data).then((res) => {
        console.log(res)
        this.$bvToast.toast('enregistrement reussie', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.get()
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast("erreur lors de l'enregistrement", {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    update (data) {
      professeurs.update(this.toModify.id, data).then((res) => {
        this.toModify = null
        console.log(res)
        this.$bvToast.toast('Modification effectuee', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.get()
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast('erreur lors de la modification', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    destroy (id) {
      professeurs.delete(id).then((res) => {
        console.log(res)
        this.$bvToast.toast('Supression reussie', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.get()
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast('erreur lors de la suppression', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    onModifyButton (data) {
      this.toModify = data
      this.form = {
        nom: data.nom,
        prenom: data.prenom,
        contact: data.contact,
      }
    },
  },
}
</script>

<style lang="scss">
</style>
