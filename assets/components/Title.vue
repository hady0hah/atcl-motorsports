<template>
  <b-row class="section-title-wrapper">
    <b-col sm="6" align-self="stretch" class="section-title">
      <h2>
        {{ title }}
      </h2>
    </b-col>
    <b-col sm="6" align-self="stretch" class="section-title-info">
      <h6>
        {{ info }}
      </h6>
    </b-col>
  </b-row>
</template>

<script>
    export default {
        name: "Title",
        props: ["title", "info"],
    }
</script>

<style scoped>
  .section-title-wrapper {
    margin-top: 2em;
    margin-bottom: 2em;
  }

  .section-title-wrapper div {
    display: flex;
  }

  .section-title {
    border-right: 1px solid #c8c6c9;
  }

  .section-title-wrapper h2 {
    margin: auto 0 auto auto;
    text-align: right;
  }

  .section-title-wrapper h6 {
    margin: auto 0;
  }

  @media only screen and (max-width: 540px) {
    .section-title-wrapper h2 {
      margin: 0;
      text-align: left;
      padding-bottom: 0.5em;
    }

    .section-title {
      border: 0;
    }
  }
</style>
