<template>
  <b-container>
    <h1>Liste des eleves</h1>
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

            <b-form-group label="Sexe">
              <b-form-select  v-model="filter.sexe" :options="sexes" class="mb-2 mr-sm-2 mb-sm-0"></b-form-select>
            </b-form-group>

            <b-form-group label="Age minimum">
              <b-form-input type="number" v-model="filter.ageMin" :options="classes" class="mb-2 mr-sm-2 mb-sm-0" />
            </b-form-group>

            <b-form-group label="Age maximum">
              <b-form-input type="number" v-model="filter.ageMax" :options="classes" class="mb-2 mr-sm-2 mb-sm-0" />
            </b-form-group>

            <b-form-group label="Eleves partis">
              <b-form-checkbox v-model="filter.parti" />
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

      <b-table striped hover :fields="fields" :items="items" :current-page="table.currentPage" :per-page="table.perPage">
        <template #cell(classe)="data">
          <span>{{  data.item.classe ? data.item.classe.libelle : '' }}</span>
        </template>
        <template #cell(options)="data">
          <div v-if="data.item.dateDepart == null">
            <b-button variant="primary" class="mr-2" @click="onModifyButton(data.item)" v-b-modal.modif-eleve>modifier</b-button>
            <b-button variant="success" class="mr-2" @click="imprimerFiche(data.item)" v-b-modal.modif-eleve v-if="false">imprimer la fiche</b-button>
            <b-button variant="danger" @click="onSortieButton(data.item)" v-b-modal.sortie-eleve>depart</b-button>
          </div>
          <div v-else>
            <b-button variant="danger" @click="annulerDepart(data.item)">annuler depart</b-button>
          </div>
        </template>

      </b-table>

      <b-modal id="imprimer-rapport" size="lg" hide-footer>
        <div>
          <iframe v-if="pdfData" :src="pdfData"></iframe>
        </div>
      </b-modal>

      <b-modal id="modif-eleve" size="lg" hide-footer>
        <div>

          <b-form @submit="update">
            <div class="p-3 border border-gray">
              <h3>Fiche de l'eleve</h3>
              <b-form-group label="Nom et prenoms">
                <b-row>
                  <b-col>
                    <b-form-input v-model="form.nom" placeholder="Nom"></b-form-input>
                  </b-col>
                  <b-col>
                    <b-form-input v-model="form.prenom" placeholder="Prenoms"></b-form-input>
                  </b-col>
                </b-row>
                <b-form-group label="Numero matricule">
                  <b-form-input v-model="form.matricule"></b-form-input>
                </b-form-group>
              </b-form-group>
              <b-form-group label="Date de naissance">
                <b-form-datepicker v-model="form.naissance"></b-form-datepicker>
              </b-form-group>
              <b-form-group label="Sexe">
                <b-form-select v-model="form.sexe" :options="sexes"></b-form-select>
              </b-form-group>
              <b-form-group label="Niveau">
                <b-form-select v-model="form.niveau_id" :options="niveaux" @change="loadClasses"></b-form-select>
              </b-form-group>
              <b-form-group label="Classe">
                <b-form-select v-model="form.classe_id" :options="classes"></b-form-select>
              </b-form-group>
              <b-form-group label="Ethnie">
                <b-form-input v-model="form.ethnie"></b-form-input>
              </b-form-group>
              <b-form-group label="Adresse actuelle">
                <b-form-textarea v-model="form.adresse"></b-form-textarea>
              </b-form-group>
            </div>
            <div class="p-3 border border-gray">
              <h3>Informations sur la mere</h3>
              <b-form-group label="Nom et prenoms">
                <b-row>
                  <b-col>
                    <b-form-input v-model="form.nomMere" placeholder="Nom"></b-form-input>
                  </b-col>
                  <b-col>
                    <b-form-input v-model="form.prenomMere" placeholder="Prenoms"></b-form-input>
                  </b-col>
                </b-row>
              </b-form-group>
              <b-form-group label="Numero de telephone">
                <b-form-input v-model="form.telephoneMere"></b-form-input>
              </b-form-group>
              <b-form-group label="Profession">
                <b-form-input v-model="form.professionMere"></b-form-input>
              </b-form-group>
            </div>

            <div class="p-3 border border-gray">
              <h3>Informations sur le pere</h3>
              <b-form-group label="Nom et prenoms">
                <b-row>
                  <b-col>
                    <b-form-input v-model="form.nomPere" placeholder="Nom"></b-form-input>
                  </b-col>
                  <b-col>
                    <b-form-input v-model="form.prenomPere" placeholder="Prenoms"></b-form-input>
                  </b-col>
                </b-row>
              </b-form-group>
              <b-form-group label="Numero de telephone">
                <b-form-input v-model="form.telephonePere"></b-form-input>
              </b-form-group>
              <b-form-group label="Profession">
                <b-form-input v-model="form.professionPere"></b-form-input>
              </b-form-group>
            </div>

            <div class="p-3 border border-gray">
              <h3>Autres informations</h3>
              <b-form-group label="Freres et soeurs">
                <b-form-input v-model="form.frereSoeur1"></b-form-input>
                <b-form-input v-model="form.frereSoeur2"></b-form-input>
                <b-form-input v-model="form.frereSoeur3"></b-form-input>
                <b-form-input v-model="form.frereSoeur4"></b-form-input>
                <b-form-input v-model="form.frereSoeur5"></b-form-input>
              </b-form-group>
              <b-form-group label="Etablissements frequentes">
                <b-form-input v-model="form.etablissement1"></b-form-input>
                <b-form-input v-model="form.etablissement2"></b-form-input>
                <b-form-input v-model="form.etablissement3"></b-form-input>
                <b-form-input v-model="form.etablissement4"></b-form-input>
                <b-form-input v-model="form.etablissement5"></b-form-input>
              </b-form-group>
            </div>

            <div class="mt-2">
              <b-button type="submit" variant="success">Inscrire</b-button>
              <b-button type="reset" variant="danger">Annuler</b-button>
            </div>
          </b-form>

        </div>
      </b-modal>

      <b-modal id="sortie-eleve" size="lg" hide-footer>
        <div>
          <b-form @submit="update">
            <div class="p-3 border border-gray">
              <h3>Sortie de l'eleve</h3>
              <b-form-group label="Date de sortie">
                <b-form-datepicker v-model="formSortie.dateDepart"></b-form-datepicker>
              </b-form-group>
              <b-form-group label="Raison de la sortie">
                <b-form-textarea v-model="formSortie.raisonDepart"></b-form-textarea>
              </b-form-group>
            </div>
            <div class="mt-2">
              <b-button type="submit" variant="success" @click="sortie">Valider</b-button>
              <b-button type="reset" variant="danger">Annuler</b-button>
            </div>
          </b-form>
        </div>
      </b-modal>

    </b-row>
  </b-container>
</template>

<script>
import eleves from '@/services/api/eleves'
import niveaux from '@/services/api/niveaux'
import classes from '@/services/api/classes'
import config from '@/services/api/config'

export default {
  name: 'eleves',
  data () {
    return {
      fields: ['matricule', 'nom', 'prenom', 'age', 'sexe', 'classe', 'Options'],
      items: [],
      niveaux: [],
      classes: [],
      sexes: [{ value: '', text: 'Tous' }, { value: 'F', text: 'Feminin' }, { value: 'M', text: 'Masculin' }],
      toModify: null,
      pdfData: null,
      filter: {
        nom: '',
        niveau: '',
        classe: '',
        sexe: '',
        ageMin: 0,
        ageMax: 100,
        parti: false,
      },
      form: {
        nom: '',
        prenom: '',
        matricule: '',
        naissance: '',
        sexe: null,
        niveau_id: '',
        classe_id: '',
        ethnie: '',
        adresse: '',

        nomMere: '',
        prenomMere: '',
        telephoneMere: '',
        professionMere: '',

        nomPere: '',
        prenomPere: '',
        telephonePere: '',
        professionPere: '',

        frereSoeur1: '',
        frereSoeur2: '',
        frereSoeur3: '',
        frereSoeur4: '',
        frereSoeur5: '',

        etablissement1: '',
        etablissement2: '',
        etablissement3: '',
        etablissement4: '',
        etablissement5: '',
      },
      formSortie: {
        dateDepart: null,
        raisonDepart: '',
      },
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
    rows () { return this.items.length },
    rapportQueryString () { return config.baseURL + '/listeeleve?' + new URLSearchParams(this.filter).toString() },

  },
  mounted () {
    // this.get()
    this.filtrer()
    this.loadNiveaux()
    this.loadClasses()
  },
  methods: {
    get () {
      eleves.get().then((res) => {
        this.items = []
        for (const e of res.data) {
          this.items.push(e)
        }
      })
    },
    update (e) {
      e.preventDefault()
      this.$bvModal.hide('gerer-eleve')
      const data = JSON.parse(JSON.stringify(this.form))
      eleves.update(this.toModify.id, data).then((res) => {
        console.log(res)
        this.toModify = null
        this.$bvToast.toast('Modification reussie', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.filtrer()
        // this.get()
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast('Erreur lors de la modification', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    onModifyButton (data) {
      console.log('data', JSON.stringify(data))
      this.toModify = data
      this.form = JSON.parse(JSON.stringify(data))
    },
    onSortieButton (data) {
      console.log('sortie data', JSON.stringify(data))
      this.toModify = data
      // this.form = json.parse(json.stringify(data))
    },
    loadNiveaux () {
      niveaux.get().then((res) => {
        this.niveaux = []
        this.niveaux.push({ value: '', text: 'Tous' })
        for (const d of res.data) {
          this.niveaux.push({ value: d.id, text: d.libelle })
        }
      })
    },
    loadClasses () {
      classes.get().then((res) => {
        this.classes = []
        const niveau = this.filter.niveau
        this.classes.push({ value: '', text: 'Tous' })
        for (const d of res.data) {
          // if (niveau == null || d.niveau_id === niveau) {
          this.classes.push({ value: d.id, text: d.libelle })
          // }
        }
      })
    },
    imprimerFiche (data) {
    },
    filtrer () {
      this.$bvToast.toast('Chargement en cours', {
        title: 'Success',
        variant: 'success',
        solid: true,
        toaster: 'b-toaster-top-center',
      })
      const data = JSON.parse(JSON.stringify(this.filter))
      eleves.filtre(data).then((res) => {
        console.log('data : ', res)
        this.items = []
        for (const e of res.data) {
          this.items.push(e)
        }
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast('erreur lors du chargement', {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    sortie (e) {
      e.preventDefault()
      this.$bvModal.hide('sortie-eleve')
      const data = JSON.parse(JSON.stringify(this.formSortie))
      console.log(data)
      eleves.sortie(this.toModify.id, data).then((res) => {
        console.log(res)
        this.toModify = null
        this.filtrer()
        // this.get()
        this.$bvToast.toast('Sortie effectuee', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast("Une erreur s'est produite", {
          title: 'error',
          variant: 'danger',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
      })
    },
    annulerDepart (data) {
      eleves.annulerDepart(data.id).then((res) => {
        console.log(res)
        this.toModify = null
        this.$bvToast.toast('depart annule', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.filtrer()
        // this.get()
      }).catch((err) => {
        this.$bvToast.toast("Une erreur s'est produite", {
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
