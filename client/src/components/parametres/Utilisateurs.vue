<template>
  <b-container>
    <b-row>
      <div class="p-5">
        <b-button variant="success" v-b-modal.gerer-profs>Ajouter un utilisateur</b-button>
        <b-table striped hover :fields="fields" :items="items">
          <template #cell(options)="data">
            <b-button variant="primary" class="mr-2" @click="onModifyButton(data.item)" v-b-modal.gerer-profs>modifier</b-button>
            <b-button v-if="deletable(data.item)" variant="danger" @click="destroy(data.item.id)">delete</b-button>
          </template>
        </b-table>

        <b-modal id="gerer-profs" size="lg" hide-footer>
          <div>
            <b-form @submit="save">
              <b-form-group label="Nom">
                <b-form-input v-model="form.nom"></b-form-input>
              </b-form-group>
              <b-form-group label="Nom d'utilisateur">
                <b-form-input v-model="form.username"></b-form-input>
              </b-form-group>
              <b-form-group label="Mot de passe">
                <b-form-input type="password" v-model="form.password"></b-form-input>
              </b-form-group>
              <b-form-group label="Role">
                <b-form-select type="password" v-model="form.role" :options="roles"></b-form-select>
              </b-form-group>
              <b-button type="submit" variant="success">Valider</b-button>
              <b-button type="reset" variant="danger">Annuler</b-button>
            </b-form>
          </div>
        </b-modal>
      </div>
    </b-row>
  </b-container>
</template>

<script>

import users from '@/services/api/users'

export default {
  data () {
    return {
      fields: [
        { key: 'nom', label: 'Nom' },
        { key: 'username', label: "Nom d'utilisateur" },
        { key: 'role', label: 'Role' },
        { key: 'options', label: 'Options' },
      ],
      items: [],
      roles: [
        { value: 'admin', text: 'Administrateur' },
        { value: 'saisie', text: 'Saisie' },
      ],
      form: {
        nom: '',
        username: '',
        password: '',
        role: '',
      },
      toModify: null,
      currentUser: null,
    }
  },
  mounted () {
    this.currentUser = this.$store.getters.getUser
    this.get()
  },
  methods: {
    get () {
      this.toModify = null
      users.get().then((res) => {
        console.log(res.data)
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
      users.save(data).then((res) => {
        console.log(res)
        this.$bvToast.toast('utilisateur enregistre', {
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
      users.update(this.toModify.id, data).then((res) => {
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
      users.delete(id).then((res) => {
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
        username: data.username,
        role: data.role,
        password: null,
      }
    },
    deletable (user) {
      if (user.deletable === 0) return false
      if (this.currentUser && this.currentUser.id === user.id) return false
      return true
    },
  },
}
</script>

<style lang="scss">
</style>
