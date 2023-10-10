<template>
  <div class="dashboard">
    <dashboard-info-block />
  </div>
</template>

<script>
import DashboardInfoBlock from './DashboardInfoBlock'

export default {
  name: 'dashboard',
  components: {
    DashboardInfoBlock,
  },
  methods: {
    addAddressToMap ({ city, country }) {
      this.$refs.dashboardMap.addAddress({ city: city.text, country })
    },
  },
}
</script>

<style lang="scss">
  .row-equal .flex {
    .va-card {
      height: 100%;
    }
  }

  .dashboard {
    .va-card {
      margin-bottom: 0 !important;
    }
  }
</style>
