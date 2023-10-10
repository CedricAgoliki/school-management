import axios from 'axios'
import config from './config'

const http = axios.create({
  baseURL: config.baseURL + '/api/eleves',
})

export default {
  get () {
    return http.get('')
  },
  save (data) {
    return http.post('', data)
  },
  update (id, data) {
    return http.put('/' + id, data)
  },
  sortie (id, data) {
    return http.put('/sortie/' + id, data)
  },
  annulerDepart (id, data) {
    return http.get('/annulersortie/' + id)
  },
  delete (id) {
    return http.delete('/' + id)
  },
  paiements (id) {
    return http.get('/' + id + '/paiements')
  },
  filtre (data) {
    return http.post('/filtre', data)
  },
  filtrePaiement (data) {
    return http.post('/filtrepaiements', data)
  },
  results (data) {
    return http.post('/resultats', data)
  },
  print (data) {
    return http.post('/print', data)
  },
  update (id, data) {
    return http.put('/sortie/' + id, data)
  },
}
