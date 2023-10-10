import axios from 'axios'
import config from './config'

const http = axios.create({
  baseURL: config.baseURL + '/api/stats',
})

export default {
  get () {
    return http.get('')
  },
}
