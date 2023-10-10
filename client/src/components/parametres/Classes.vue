<template>
  <b-row>
    <div class="p-5">
      <b-button variant="success" @click = "toModify = null" v-b-modal.gerer-classe>Ajouter classe</b-button>
      <b-table striped hover :fields="fields" :items="items">
        <template #cell(niveau)="data">
          <span>{{ data.item.niveau.libelle }}</span>
        </template>
        <template #cell(options)="data">
          <b-button variant="primary" class="mr-2" @click="onModifyButton(data.item)" v-b-modal.gerer-classe>modifier</b-button>
          <b-button variant="danger" @click="destroy(data.item.id)">delete</b-button>
        </template>
      </b-table>

      <b-modal id="gerer-classe" size="lg" hide-footer>
        <div>
          <b-form @submit="save">
            <b-form-group label="Libelle">
              <b-form-input v-model="form.libelle"></b-form-input>
            </b-form-group>
            <b-form-group label="Niveau">
              <b-form-select v-model="form.niveau" :options="niveaux"></b-form-select>
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
import classes from '@/services/api/classes'

export default {
  name: 'classes',
  data () {
    return {
      fields: ['libelle', 'description', 'niveau', 'options'],
      items: [],
      niveaux: [],
      form: {
        libelle: '',
        description: '',
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
      niveaux.get().then((res) => {
        this.niveaux = []
        for (const d of res.data) {
          this.niveaux.push({
            value: d.id,
            text: d.libelle,
          })
        }
      })

      classes.get().then((res) => {
        this.items = []
        for (const d of res.data) {
          this.items.push(d)
        }
      })
    },
    save (e) {
      e.preventDefault()
      this.$bvModal.hide('gerer-classe')
      console.log(JSON.stringify(this.form))
      console.log(e)
      const data = {
        libelle: this.form.libelle,
        description: this.form.description,
        niveau_id: this.form.niveau,
      }
      if (this.toModify) this.update(data)
      else this.add(data)
    },
    add (data) {
      classes.save(data).then((res) => {
        console.log(res)
        this.$bvToast.toast('classe enregistree', {
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
      console.log('tochange ', JSON.stringify(this.toModify))
      classes.update(this.toModify.id, data).then((res) => {
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
      classes.delete(id).then((res) => {
        console.log('here')
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
        libelle: data.libelle,
        niveau: data.niveau_id,
        description: data.description,
      }
    },
  },
}
</script>

<style lang="scss">
</style>
