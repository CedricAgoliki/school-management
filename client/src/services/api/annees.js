import axios from 'axios'
import config from './config'

const http = axios.create({
  baseURL: config.baseURL + '/api/annees',
})

export default {
  ouverture (data) {
    return http.post('', data)
  },
  fermeture () {
    return http.put('')
  },
}
