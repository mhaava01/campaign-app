import axios from 'axios'
import {route as ziggyJs, RouteParams} from 'ziggy-js'
import { Ziggy } from '../../ziggy.js'

export const route = (name: string, params?: RouteParams<any> | undefined): string => ziggyJs(name, params, false, Ziggy)

const baseURL = import.meta.env.VITE_API_URL

const api = axios.create({
  baseURL,
  headers: {
    'Content-Type': 'application/json',
  },
  withCredentials: true,
  withXSRFToken: true,
})

api.defaults.withCredentials = true

export default api
