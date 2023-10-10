<template>
  <b-container>
    <h1>Rapport sur ecolage</h1>
    <b-row>
      <b-col cols="12">
        <div class="p-3 border border-gray">
          <h3>Filtre</h3>
          <b-form  inline class="mb-2">
            <b-form-group label="Nom">
              <b-input id="nom-eleve-filtre" v-model="filter.nom" placeholder="nom de l'eleve" class="mb-2 mr-sm-2 mb-sm-0"></b-input>
            </b-form-group>

            <b-form-group label="Niveau">
              <b-form-select id="niveau-eleve-filtre" v-model="filter.niveau" placeholder="nom de l'eleve" :options="niveaux" class="mb-2 mr-sm-2 mb-sm-0" @change="loadClasses()"></b-form-select>
            </b-form-group>

            <b-form-group label="Classe">
              <b-form-select id="classe-eleve-filtre" v-model="filter.classe" :options="classes" class="mb-2 mr-sm-2 mb-sm-0"></b-form-select>
            </b-form-group>

            <b-form-group label="montant minimum">
              <b-form-input type="number" v-model="filter.montantMin" :options="classes" class="mb-2 mr-sm-2 mb-sm-0" />
            </b-form-group>

            <b-form-group label="montant maximum">
              <b-form-input type="number" v-model="filter.montantMax" :options="classes" class="mb-2 mr-sm-2 mb-sm-0" />
            </b-form-group>

          </b-form>

          <b-button variant="primary" @click="filtrer">Appliquer</b-button>
          <b-button variant="primary" class="ml-2"  target="_blank" :href="rapportQueryString">Imprimer</b-button>
        </div>
      </b-col>

      <b-col :cols="12">
        <div class="p-3 border border-gray">
          <b-col :cols="12">
            <div>
              <div>Total: {{ rows }}</div>
              <div>Par page : <b-form-select v-model="table.perPage" :options="table.options"></b-form-select></div>
            </div>
          </b-col>

          <b-col :cols="12">
            <b-pagination v-model="table.currentPage" :total-rows="rows" :per-page="table.perPage"></b-pagination>
          </b-col>
        </div>
      </b-col>

      <b-table striped hover :fields="eleves.fields" :items="eleves.items" :current-page="table.currentPage" :per-page="table.perPage">
        <template #cell(classe)="data">
          <span>{{ data.item.classe.libelle }}</span>
        </template>
        <template #cell(options)="data">
          <b-button variant="primary" @click="loadPaiements(data.item.id)" v-b-modal.liste-paiements>Liste des paiements</b-button>
        </template>
      </b-table>

      <b-modal id="liste-paiements" size="lg" hide-footer>
        <div>
          <b-table striped hover :fields="paiements.fields" :items="paiements.items">
            <template #cell(options)="data">
              <b-button variant="primary" @click="supprimerPaiement(data.item)">Supprimer</b-button>
            </template>
          </b-table>
        </div>
      </b-modal>
    </b-row>
  </b-container>
</template>

<script>
import eleves from '@/services/api/eleves'
import niveaux from '@/services/api/niveaux'
import classes from '@/services/api/classes'
import paiements from '@/services/api/paiements'
import config from '@/services/api/config'

export default {
  name: 'eleves',
  data () {
    return {
      eleves: {
        fields: [
          { key: 'nom', label: 'Nom' },
          { key: 'prenom', label: 'Prenom' },
          { key: 'classe', label: 'Classe' },
          { key: 'montantTotalPaiement', label: 'Montant Paye' },
          { key: 'options', label: 'Options' },
        ],
        items: [],
      },
      paiements: {
        fields: ['montant', 'date', 'infos', 'options'],
        items: [],
      },
      filter: {
        nom: '',
        montantMin: 0,
        montantMax: 1000000,
        niveau: '',
        classe: '',
      },
      niveaux: [],
      classes: [],
      table: {
        perPage: 30,
        currentPage: 1,
        options: [
          { value: 10, text: '10' },
          { value: 20, text: '20' },
          { value: 30, text: '30' },
          { value: 50, text: '50' },
          { value: 100, text: '100' },
        ],
      },
    }
  },
  computed: {
    rows: function () { return this.eleves.items.length },
    rapportQueryString () { return config.baseURL + '/listepaiement?' + new URLSearchParams(this.filter).toString() },
  },
  mounted () {
    // this.loadEleves()
    this.filtrer()
    this.loadNiveaux()
    this.loadClasses()
  },
  methods: {
    loadEleves () {
      eleves.get().then((res) => {
        console.log(res)
        this.eleves.items = []
        for (const d of res.data) {
          this.eleves.items.push(d)
        }
      })
    },
    loadNiveaux () {
      niveaux.get().then((res) => {
        this.niveaux = [{ value: '', text: 'Tous' }]
        for (const d of res.data) {
          this.niveaux.push({ value: d.id, text: d.libelle })
        }
      })
    },
    loadClasses () {
      classes.get().then((res) => {
        this.classes = [{ value: '', text: 'Tous' }]
        const niveau = this.filter.niveau
        for (const d of res.data) {
          if (niveau == null || d.niveau_id === niveau) {
            this.classes.push({
              value: d.id,
              text: d.libelle,
            })
          }
        }
      })
    },
    loadPaiements (eleve) {
      console.log(eleve)
      eleves.paiements(eleve).then((res) => {
        this.paiements.items = []
        for (const d of res.data) {
          this.paiements.items.push(d)
        }
      })
    },
    filtrer () {
      console.log(JSON.stringify(this.filter))
      const data = JSON.parse(JSON.stringify(this.filter))
      eleves.filtrePaiement(data).then((res) => {
        console.log(res)
        this.eleves.items = []
        for (const d of res.data) {
          this.eleves.items.push(d)
        }
      })
    },
    imprimerRapport () {},
    supprimerPaiement (paiement) {
      console.log(JSON.stringify(paiement))
      paiements.delete(paiement.id).then((res) => {
        this.$bvToast.toast('Paiement de ' + paiement.montant + ' supprime', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        // this.loadEleves()
        this.filter()
        this.loadPaiements(paiement.eleve_id)
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast('Erreur lors de la suppression', {
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
</style>
