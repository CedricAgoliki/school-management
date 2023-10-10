import axios from 'axios'
import config from './config'

const http = axios.create({
  baseURL: config.baseURL + '/api/examens',
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
  eleves (id) {
    return http.get('/' + id + '/eleves')
  },
  insertEleves (id, data) {
    return http.post('/' + id + '/eleves', data)
  },
}
