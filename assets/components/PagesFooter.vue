<template>
  <b-container class="pages-footer" fluid>
    <b-row no-gutters align-v="stretch">
      <b-col md="6" class="rights">
        © 2021 ATCL Motorsports, ALL RIGHTS RESERVED
      </b-col>
      <b-col md="6" class="obsoft">
        <div style="line-height: 3.2em;">
          Engineered by
        </div>
        <div>
          <a href="https://www.potech.global" target="_blank"></a>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
    export default {
        name: "PagesFooter"
    }
</script>

<style scoped>
  .rights {
    align-self: center;
  }

  .obsoft {
    justify-content: flex-end;
    display: flex;
    padding-top: 1em;
  }

  .obsoft div {
    line-height: 3.2em;
  }

  .obsoft a {
    background-image: url(../images/oblogo-white.png);
    width: 170px;
    padding-top: 38px;
    margin-left: 5px;
    display: inline-block;
    background-size: contain;
    background-repeat: no-repeat;
  }

  .pages-footer {
    background-color: #0d0d0d;
    color: white;
    padding: 1em 5%;
    font-size: 12px;
  }
</style>
