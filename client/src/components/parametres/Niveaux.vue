<template>
  <b-row>
    <div class="p-5">
      <b-button variant="success" v-b-modal.gerer-niveau>Ajouter niveau</b-button>
      <b-table striped hover :fields="fields" :items="items">
        <template #cell(options)="data">
          <b-button variant="primary" class="mr-2" @click="onModifyButton(data.item)" v-b-modal.gerer-niveau>modifier</b-button>
          <b-button variant="danger" @click="destroy(data.item.id)">delete</b-button>
        </template>
      </b-table>

      <b-modal id="gerer-niveau" size="lg" hide-footer>
        <div>
          <b-form @submit="save">
            <b-form-group label="Libelle">
              <b-form-input v-model="form.libelle"></b-form-input>
            </b-form-group>
            <b-form-group label="Description">
              <b-form-textarea v-model="form.description"></b-form-textarea>
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

export default {
  name: 'niveaux',
  data () {
    return {
      fields: ['libelle', 'description', 'options'],
      items: [],
      form: {
        libelle: '',
        description: '',
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
      niveaux.get().then((res) => {
        this.items = []
        for (const d of res.data) {
          this.items.push(d)
        }
      })
    },
    save (e) {
      e.preventDefault()
      this.$bvModal.hide('gerer-niveau')
      console.log(e)
      const data = {
        libelle: this.form.libelle,
        description: this.form.description,
      }
      if (this.toModify) this.update(data)
      else this.add(data)
    },
    add (data) {
      niveaux.save(data).then((res) => {
        console.log(res)
        this.$bvToast.toast('niveau enregistree', {
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
      niveaux.update(this.toModify.id, data).then((res) => {
        console.log(res)
        this.toModify = null
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
      niveaux.delete(id).then(() => {
        this.toModify = null
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
      this.form = this.toModify
    },
  },
}
</script>

<style lang="scss">
</style>
