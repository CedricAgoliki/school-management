<template>
  <b-row>
    <div class="p-5">
      <b-button variant="success" v-b-modal.gerer-matiere>Ajouter matiere</b-button>
      <b-table striped hover :fields="fields" :items="items">
        <template #cell(niveau)="data">
          <span>{{ data.item.niveau.libelle }}</span>
        </template>
        <template #cell(options)="data">
          <b-button variant="primary" class="mr-2" @click="onModifyButton(data.item)" v-b-modal.gerer-matiere>modifier</b-button>
          <b-button variant="danger" @click="destroy(data.item.id)">delete</b-button>
        </template>
      </b-table>

      <b-modal id="gerer-matiere" size="lg" hide-footer>
        <div>
          <b-form @submit="save">
            <b-form-group label="Nom">
              <b-form-input v-model="form.nom"></b-form-input>
            </b-form-group>
            <b-form-group label="Code">
              <b-form-input readonly v-model="form.code"></b-form-input>
            </b-form-group>
            <b-form-group label="Niveau">
              <b-form-select v-model="form.niveau" :options="niveaux"></b-form-select>
            </b-form-group>
            <b-form-group label="Coefficient">
              <b-form-input v-model="form.coef"></b-form-input>
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

import niveaux from '@/services/api/niveaux'
import matieres from '@/services/api/matieres'

export default {
  name: 'matieres',
  data () {
    return {
      fields: ['code', 'nom', 'coef', 'niveau', 'options'],
      items: [],
      niveaux: [],
      form: {
        code: '',
        nom: '',
        coef: '',
        niveau: null,
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
      matieres.get().then((res) => {
        this.items = []
        for (const d of res.data) {
          this.items.push(d)
        }
      })
      niveaux.get().then((res) => {
        this.niveaux = []
        for (const d of res.data) {
          this.niveaux.push({
            value: d.id,
            text: d.libelle,
          })
        }
      })
    },
    save (e) {
      e.preventDefault()
      this.$bvModal.hide('gerer-matiere')
      console.log(e)
      const data = {
        nom: this.form.nom,
        code: this.form.nom,
        niveau_id: this.form.niveau,
        coef: this.form.coef,
      }
      if (this.toModify) this.update(data)
      else this.add(data)
    },
    add (data) {
      matieres.save(data).then((res) => {
        console.log(res)
        this.$bvToast.toast('matiere enregistree', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.get()
      }).catch((err) => {
        this.$bvToast.toast("erreur lors de l'enregistrement", {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    update (data) {
      matieres.update(this.toModify.id, data).then((res) => {
        console.log(res)
        this.$bvToast.toast('Modification effectuee', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.get()
      }).catch((err) => {
        this.$bvToast.toast('erreur lors de la modification', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    destroy (id) {
      matieres.delete(id).then((res) => {
        console.log(res)
        this.$bvToast.toast('Supression reussie', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.get()
      }).catch((err) => {
        this.$bvToast.toast('erreur lors de la suppression', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    onModifyButton (data) {
      console.log(JSON.stringify(data))
      this.$bvModal.hide('gerer-matiere')
      this.toModify = data
      this.form = {
        nom: data.nom,
        prenoms: data.prenoms,
        niveau: data.niveau_id,
        coef: data.coef,
      }
    },
  },
}
</script>

<style lang="scss">
</style>
