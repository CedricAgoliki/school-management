<template>
  <form @submit.prevent="onsubmit">
    <va-input
      v-model="username"
      type="text"
      label="Nom d'utilisateur"
      :error="!!usernameErrors.length"
      :error-messages="usernameErrors"
    />

    <va-input
      v-model="password"
      type="password"
      label="Mot de passe"
      :error="!!passwordErrors.length"
      :error-messages="passwordErrors"
    />

    <!--<div class="auth-layout__options d-flex align--center justify--space-between">
      <va-checkbox v-model="keepLoggedIn" class="mb-0" :label="$t('auth.keep_logged_in')"/>
      <router-link class="ml-1 link" :to="{name: 'recover-password'}">{{$t('auth.recover_password')}}</router-link>
    </div>-->

    <div class="d-flex justify--center mt-3">
      <va-button type="submit" class="my-0">Connexion</va-button>
    </div>
  </form>
</template>

<script>
import users from '@/services/api/users'

export default {
  name: 'login',
  data () {
    return {
      username: '',
      password: '',
      keepLoggedIn: false,
      usernameErrors: [],
      passwordErrors: [],
    }
  },
  computed: {
    formReady () {
      return !this.usernameErrors.length && !this.passwordErrors.length
    },
  },
  mounted () {
    this.$store.commit('setUser', null)
  },
  methods: {
    onsubmit () {
      this.usernameErrors = this.username ? [] : ['Le nom d\'utilisateur est obligatoire']
      this.passwordErrors = this.password ? [] : ['Le mot de passe est obligatoire']
      if (!this.formReady) {
        return
      }
      const data = { username: this.username, password: this.password }
      users.auth(data).then((res) => {
        const user = res.data
        this.$store.commit('setUser', user)
        this.$router.push({ name: 'dashboard' })
      }).catch(() => {
        this.$bvToast.toast("Nom d'utilisateur ou mot de passe incorrect", {
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
