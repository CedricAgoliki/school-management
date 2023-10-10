<template>
  <b-container>
    <h3>Resultats des examens</h3>
    <b-row>

      <b-col cols="12">
        <div class="p-3 border border-gray">
          <h3>Filtre</h3>
          <b-form  inline class="mb-2">

            <b-form-group label="Classe">
              <b-form-select id="classe-eleve-filtre" v-model="filter.classe" :options="classes" class="mb-2 mr-sm-2 mb-sm-0"></b-form-select>
            </b-form-group>

            <b-form-group label="Sexe">
              <b-form-select  v-model="filter.sexe" :options="sexes" class="mb-2 mr-sm-2 mb-sm-0"></b-form-select>
            </b-form-group>

          </b-form>

          <b-button variant="primary" @click="get">Appliquer</b-button>
          <b-button variant="primary" class="ml-2"  target="_blank" :href="rapportQueryString" :disabled="filter.classe == ''">Imprimer</b-button>
          <b-button variant="primary" class="ml-2" target="_blank" :href="bulletinQueryString" :disabled="filter.classe == ''">Imprimer les bulletins</b-button>
        </div>
      </b-col>

      <b-table striped hover :fields="fields" :items="items">
        <template #cell(options)="data">
          <b-button variant="success" class="mr-2" @click="selected = data.item" v-b-modal.voir-bulletin v-if="false">voir le bulletin</b-button>
        </template>

      </b-table>

      <b-modal id="voir-bulletin" size="lg" hide-footer>
        <div>
          bulletin
        </div>
      </b-modal>

    </b-row>
  </b-container>
</template>

<script>
import eleves from '@/services/api/eleves'
import classes from '@/services/api/classes'
import config from '@/services/api/config'

export default {
  data () {
    return {
      fields: ['#', 'nom', 'prenom', 'moyenne', 'sexe', 'classe'],
      items: [],
      classes: [],
      sexes: [{ value: '', text: 'Tous' }, { value: 'F', text: 'Feminin' }, { value: 'M', text: 'Masculin' }],
      selected: null,
      filter: {
        sexe: '',
        classe: '',
      },
    }
  },
  computed: {
    rapportQueryString () { return config.baseURL + '/resultatsexamen?' + new URLSearchParams(this.filter).toString() },
    bulletinQueryString () { return config.baseURL + '/bulletins?' + new URLSearchParams(this.filter).toString() },
  },
  mounted () {
    this.getClasses()
  },
  methods: {
    getClasses () {
      classes.get().then((res) => {
        this.classes = []
        for (const d of res.data) {
          this.classes.push({ value: d.id, text: d.libelle })
        }
      })
    },
    get () {
      const data = JSON.parse(JSON.stringify(this.filter))
      eleves.results(data).then((res) => {
        console.log(res)
        this.items = []
        for (const d of res.data) {
          d.classe = d.classe.libelle
          this.items.push(d)
        }
      })
    },
    imprimer () {
    },
  },
}
</script>

<style lang="scss">
</style>
