import axios from 'axios'
import config from './config'

const http = axios.create({
  baseURL: config.baseURL + '/api/niveaux',
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
  delete (id) {
    return http.delete('/' + id)
  },
  eleves (niveau) {
    return http.get('/' + niveau + '/eleves')
  },
}
