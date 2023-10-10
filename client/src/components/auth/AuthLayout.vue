<template>
  <div class="auth-layout row align-content--center">
    <img style="width: 300px; margin-left: auto; margin-right: auto;" src="../../../public/img/logo.jpg" />
    <div class="flex xs12 pa-3 flex-center">
      <router-link class="py-3 flex-center" to="/">
        <!--<va-icon-vuestic />-->
        <p style="font-size: 20px; font-weight: bold;">Notre Dame de L'esparance - Kpogan</p>
      </router-link>
    </div>

    <div class="flex xs12 pa-3">
      <div class="d-flex justify--center">
        <va-card class="auth-layout__card">
          <va-tabs
            v-model="tabIndex"
            center
          >
            <va-tab>Veuillez vous connecter</va-tab>
            <!-- <va-tab>{{ $t('auth.createNewAccount') }}</va-tab> -->
          </va-tabs>

          <va-separator/>

          <div class="pa-3">
            <router-view/>
          </div>
        </va-card>
      </div>
    </div>
  </div>
</template>

<script>
// import VaIconVuestic from '../../iconset/VaIconVuestic'

const tabs = [
  'login',
  'signup',
]

export default {
  name: 'AuthLayout',
  // components: { VaIconVuestic },
  data () {
    return {
      selectedTabIndex: 0,
      tabTitles: ['login', 'createNewAccount'],
    }
  },
  computed: {
    tabIndex: {
      set (tabIndex) {
        this.$router.push({ name: tabs[tabIndex] })
      },
      get () {
        return tabs.indexOf(this.$route.name)
      },
    },
  },
}
</script>

<style lang="scss">
.auth-layout {
  min-height: 100vh;

  &__card {
    width: 100%;
    max-width: 600px;
  }

  &__options {
    @include media-breakpoint-down(xs) {
      flex-direction: column;
    }
  }
}
</style>
