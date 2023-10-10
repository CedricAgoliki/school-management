import axios from 'axios'
import config from './config'

const http = axios.create({
  baseURL: config.baseURL + '/api/users',
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
  auth (data) {
    return http.post('/auth', data)
  },
}
