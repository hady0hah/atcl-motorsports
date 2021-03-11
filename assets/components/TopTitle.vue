<template>
    <section class="top-title-section" :style="'background-image: linear-gradient(white, white),url(\''+backgroundImage+'\');'">
    <b-container class="w-100">
      <b-row class="w-100">
        <b-col cols="12" class="back-title">
          <h1>{{ backTitle }}</h1>
        </b-col>
<!--        <b-col cols="12" class="logo-wrapper">-->
<!--          <img :src="require('../images/logo-ATCL.png')"/>-->
<!--        </b-col>-->
        <b-col lg="6" class="top-title-col">
          <div class="top-title-wrapper">
            <h5>{{ aboveTitle }}</h5>
            <h1>{{ mainTitle }}</h1>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </section>
</template>

<script>
    export default {
        name: "TopTitle",
        props: ["backTitle", "mainTitle","aboveTitle", "backImage"],
        data() {
            return {
                backgroundImage: require('../images/race-rally-racer-track-wallpaper.jpg'),
            }
        },
        mounted() {
            if(this.backImage){
                this.backgroundImage = this.backImage;
            }
        }
    }
</script>

<style scoped>
  .top-title-section {
    position: relative;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    height: 50vh;
    /*background-image:*/
    /*        linear-gradient(white, white),*/
    /*        !*url("../images/race-rally-racer-track-wallpaper.jpg");*!*/
    background-blend-mode: saturation;
  }

  .top-title-section:before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: 0.7;
    background-color: #e8e8e8;
  }

  .back-title {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    color: white;
    display: flex;
    text-align: center;
  }

  .back-title h1 {
    margin: auto;
    font-size: 10vw;
  }

  .top-title-col {
    margin-top: 10vh;
    height: 40vh;
    display: flex;
  }

  .top-title-wrapper {
    margin: auto 0;
  }

</style>
