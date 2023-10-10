<template>
  <b-container>
    <b-row>
      <b-col cols="12">
        <!-- <div class="p-5" style="border: 1px solid #cccccc;">
          <h3>Assignation automatique des eleves aux classes</h3>
          <b-button variant="primary">Assigner automatiquement</b-button>
        </div>-->
      </b-col>
      <b-col cols="12">
        <b-form @submit="save">
          <div class="p-5 mt-2" style="border: 1px solid #cccccc;">
            <h3>Assignation des eleves</h3>
            <b-row>
              <b-col cols="5">
                <b-row>
                  Selectionner le niveau
                  <b-form-select class="my-2" :options="niveaux" v-model="filter.niveau" @change="loadClasses(); loadElevesNiveau()"></b-form-select>
                </b-row>
                <b-row>
                  <b-form-select multiple :options='eleves.niveau' v-model="selected.niveau"></b-form-select>
                </b-row>
              </b-col>
              <b-col cols="2" class="pt-5">
                <b-row>
                  <b-button variant="primary" class="mx-auto my-2" @click="sendToClass" :disabled="this.filter.niveau == null || this.filter.classe == null">
                    <b-icon-arrow-right></b-icon-arrow-right>
                  </b-button>
                </b-row>
                <b-row>
                  <b-button variant="primary" class="mx-auto" @click="sendToNiveau" :disabled="this.filter.niveau == null || this.filter.classe == null">
                    <b-icon-arrow-left></b-icon-arrow-left>
                  </b-button>
                </b-row>
              </b-col>
              <b-col cols="5">
                <b-row>
                  Selectionnez la classe
                  <b-form-select class="my-2" :options="classes" v-model="filter.classe" @change="loadElevesClasse()"></b-form-select>
                </b-row>
                <b-row>
                  <b-form-select multiple :options="eleves.classe" v-model="selected.classe"></b-form-select>
                </b-row>
                <b-button type="submit" variant="success" class="mt-3">Valider</b-button>
              </b-col>
            </b-row>
          </div>
        </b-form>
      </b-col>

    </b-row>
  </b-container>
</template>

<script>
import niveaux from '@/services/api/niveaux'
import classes from '@/services/api/classes'
// import eleves from '@/services/api/eleves'

export default {
  name: 'eleves',
  data () {
    return {
      fields: ['Nom', 'Prenoms', 'Classe', 'Sexe', 'Options'],
      items: [],
      niveaux: [],
      classes: [],
      filter: {
        niveau: null,
        classe: null,
      },
      eleves: {
        niveau: [],
        classe: [],
      },
      selected: {
        niveau: [],
        classe: [],
      },
    }
  },
  mounted () {
    this.loadNiveaux()
  },
  methods: {
    loadNiveaux () {
      niveaux.get().then((res) => {
        this.niveaux = []
        for (const d of res.data) {
          this.niveaux.push({ value: d.id, text: d.libelle })
        }
      })
    },
    loadClasses () {
      classes.get().then((res) => {
        this.classes = []
        const niveau = this.filter.niveau
        for (const d of res.data) {
          // if (d.niveau_id === niveau) {
          this.classes.push({ value: d.id, text: d.libelle })
          // }
        }
      })
    },
    loadElevesNiveau () {
      niveaux.eleves(this.filter.niveau).then((res) => {
        this.eleves.niveau = []
        console.log(res.data)
        for (const d of res.data) {
          this.eleves.niveau.push({
            value: d,
            text: d.nom + ' ' + d.prenom,
          })
        }
        this.eleves.niveau = this.eleves.niveau.filter((eleve) => {
          for (const e of this.eleves.classe) {
            if (e.value.id === eleve.value.id) return false
          }
          return true
        })
      })
    },
    loadElevesClasse () {
      classes.eleves(this.filter.classe).then((res) => {
        this.eleves.classe = []
        for (const d of res.data) {
          console.log('d : ', d)
          this.eleves.classe.push({
            value: d,
            text: d.nom + ' ' + d.prenom,
          })
        }
        this.loadElevesNiveau()
      })
    },
    save (e) {
      e.preventDefault()
      const eleves = []
      for (const d of this.eleves.classe) {
        eleves.push(d.value.id)
      }
      const data = { data: eleves.join() }
      console.log(data)
      classes.insertEleves(this.filter.classe, data).then((res) => {
        this.$bvToast.toast('Transfert reussie', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast("Echec - Une erreur s'est produite", {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    /* SPAGHETTI CODE: TO BE REVIEWED */
    sendToClass () {
      console.log('sending to class')
      for (const d of this.selected.niveau) {
        this.eleves.classe.push({
          value: d,
          text: d.nom + ' ' + d.prenom,
        })
      }

      this.eleves.niveau = this.eleves.niveau.filter((el) => {
        for (const e of this.selected.niveau) {
          if (el.value.id === e.id) return false
        }
        return true
      })

      this.selected.niveau = []
    },
    sendToNiveau () {
      for (const d of this.selected.classe) {
        this.eleves.niveau.push({
          value: d,
          text: d.nom + ' ' + d.prenom,
        })
      }

      this.eleves.classe = this.eleves.classe.filter((el) => {
        for (const e of this.selected.classe) {
          if (el.value.id === e.id) return false
        }
        return true
      })

      this.selected.classe = []
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
