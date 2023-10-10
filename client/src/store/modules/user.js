const state = {
  user: null,
}

const getters = {
  getUser: state => { return state.user },
}

const mutations = {
  setUser (state, user) {
    state.user = user
  },
}

const actions = {
}

export default {
  state,
  getters,
  mutations,
  actions,
}
