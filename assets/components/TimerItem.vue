<template>
  <b-col lg="3" sm="6">
    <div style="border: 1px solid #e7e7e7;">
      <h6>{{ item }}</h6>
      <h1>{{ value }}</h1>
    </div>
  </b-col>
</template>

<script>
    export default {
        name: "TimerItem",
        props: ["item", "value"]
    }
</script>

<style scoped>
  div {
    padding: 2vw;
  }
  h6 {
    letter-spacing: 0;
    margin: 0;
  }
  h1 {
    text-align: center;
  }
</style>
