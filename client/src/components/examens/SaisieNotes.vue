<template>
  <b-container>
    <b-row>
      <b-col cols="12">
        <div class="p-5" style="border: 1px solid #cccccc;">
          <h3>Saisie des notes</h3>
          <b-form inline>
            <label>Selectionnez la classe
              <b-form-select v-model="filter.classe" :options="classes"></b-form-select>
            </label>
            <b-button variant="primary" @click="loadMatieres()">Charger</b-button>
          </b-form>
        </div>
      </b-col>
      <b-col cols="12">
        <div class="p-5 mt-2" style="border: 1px solid #cccccc;">
          <div v-for="eleve in eleves" :key="eleve.id">
            <h3>{{ eleve.nom + ' ' + eleve.prenom }}</h3>
            <b-table :fields="exams.fields" :items="matieres">
              <template #cell(matieres)="data">
                {{ data.item.nom }}
              </template>
              <template #cell(note1)="data" >
                <b-input v-model="notes[eleve.id][data.item.id].ev1"></b-input>
              </template>
              <template #cell(note2)="data">
                <b-input v-model="notes[eleve.id][data.item.id].ev2"></b-input>
              </template>
              <template #cell(note3)="data">
                <b-input v-model="notes[eleve.id][data.item.id].ev3"></b-input>
              </template>
              <template #cell(compo)="data">
                <b-input v-model="notes[eleve.id][data.item.id].comp"></b-input>
              </template>

            </b-table>
          </div>
          <b-button variant="success" @click="save">Valider</b-button>
        </div>
      </b-col>

    </b-row>
  </b-container>
</template>

<script>

import classes from '@/services/api/classes'
import notes from '@/services/api/notes'

export default {
  name: 'eleves',
  data () {
    return {
      classes: [],
      eleves: [],
      matieres: [],
      examens: [],
      filter: {
        classe: '',
      },
      exams: {
        fields: [
          { key: 'matieres', label: 'Matieres' },
          { key: 'note1', label: 'Note 1' },
          { key: 'note2', label: 'Note 2' },
          { key: 'note3', label: 'Note 3' },
          { key: 'compo', label: 'Composition' },
        ],
      },
      notes: {},
    }
  },
  mounted () {
    this.loadClasses()
  },
  methods: {
    loadClasses () {
      classes.get().then((res) => {
        this.classes = []
        for (const d of res.data) {
          this.classes.push({
            value: d.id,
            text: d.libelle,
          })
        }
      })
    },
    loadMatieres () {
      console.log('loading matieres')
      classes.matieres(this.filter.classe).then((res) => {
        this.matieres = []
        for (const d of res.data) {
          this.matieres.push(d)
        }
        console.log('matieres', this.matieres)
        // this.loadExamens()
        this.loadEleves()
      })
    },
    loadEleves () {
      console.log('loading eleves')
      classes.eleves(this.filter.classe).then((res) => {
        this.eleves = []
        for (const d of res.data) {
          this.eleves.push(d)
          this.notes[d.id] = {}
        }
        this.setNotes()
      })
    },
    setNotes () {
      for (const e of this.eleves) {
        for (const m of this.matieres) {
          this.notes[e.id][m.id] = {
            ev1: '',
            ev2: '',
            ev3: '',
            comp: '',
          }
        }
      }
      console.log('notes', this.notes)
      this.loadNotes()
    },
    loadNotes () {
      notes.classe(this.filter.classe).then((res) => {
        console.log(res.data)
        for (const d of res.data) {
          this.notes[d.eleve_id][d.matiere_id][d.examen.code] = d.valeur
        }
        console.log('forcing update', this.notes)
        this.$forceUpdate()
      })
    },
    save () {
      const data = JSON.parse(JSON.stringify(this.notes))
      notes.save(data).then((res) => {
        this.$bvToast.toast('Notes enregistrees', {
          title: 'Success',
          variant: 'success',
          solid: true,
          toaster: 'b-toaster-top-center',
        })
        this.loadMatieres()
      }).catch((err) => {
        console.log(err)
        this.$bvToast.toast("erreur l'enregistrement des notes", {
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
