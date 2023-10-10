<template>
  <b-container>
    <div>
      <b-form @submit="save">
        <div class="p-3 border border-gray">
          <h3>Informations sur l'eleve</h3>
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
  </b-container>
</template>

<script>
import niveaux from '@/services/api/niveaux'
import classes from '@/services/api/classes'
import eleves from '@/services/api/eleves'

export default {
  name: 'eleves',
  data () {
    return {
      niveaux: [],
      classes: [],
      sexes: [{ value: 'F', text: 'Feminin' }, { value: 'M', text: 'Masculin' }],
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
          this.niveaux.push({
            value: d.id,
            text: d.libelle,
          })
        }
      })
    },
    loadClasses () {
      classes.get().then((res) => {
        console.log('classes ', res)
        this.classes = []
        const niveau = this.form.niveau_id
        for (const d of res.data) {
          if (d.niveau_id === niveau) {
            this.classes.push({
              value: d.id,
              text: d.libelle,
            })
          }
        }
      })
    },
    save (e) {
      e.preventDefault()
      console.log(this.form)
      const data = JSON.parse(JSON.stringify(this.form))
      console.log('data', data)
      eleves.save(data).then((res) => {
        this.$bvToast.toast('Inscription reussie', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })

        this.form = {
          nom: '',
          prenom: '',
          sexe: '',
          naissance: '',
          niveau: '',
          classe_id: '',
          nomTuteur: '',
          prenomTuteur: '',
          contactTuteur: '',
        }
      }).catch((err) => {
        console.log(err)
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
</style>
