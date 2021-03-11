<template>
  <section class="banner-section-wrapper">
    <div class="banner-section" :class="[topEvent.classes,index === slide ? 'active' : 'not-active']"
         v-for="(topEvent, index) in topEvents" v-if="topEvents.length && topEvents.length>0"
         :style="{ backgroundImage: 'url(\'' + topEvent.bannerFilePath +'/' + topEvent.banner + '\')' }">
      <b-container class="banner-wrapper w-100" fluid>
        <b-row class="w-100">
          <b-col lg="6" offset-lg="6">
            <b-row>
              <b-col sm="10">
                <h5>{{ topEvent.championship.label }}</h5>
                <h1>{{ topEvent.label }}</h1>
<!--                <p>{{ topEvent.description }}</p>-->
              </b-col>
              <b-col sm="1" class="banner-side" offset-sm="1">
                <p class="banner-date">{{ topDate }}</p>
                <p class="banner-location">{{ topEvent.location }}</p>
              </b-col>
            </b-row>
          </b-col>
          <b-col sm="3" class="arrows-wrapper" @click="changeEvent(-1)">
            <div class="arrow-square"></div>
            <span class="line arrow-left"></span>
            <p>Prev</p>
          </b-col>
          <b-col sm="3" offset-sm="6" class="arrows-wrapper" @click="changeEvent(1)">
            <div class="arrow-square" style="right: 0"></div>
            <p>Next</p><span class="line arrow-right"></span>
          </b-col>
        </b-row>
      </b-container>
    </div>
  </section>
</template>

<script>
    import {listTopEvents} from "../api/events";

    export default {
        name: "TopEvent",
        data() {
            return {
                topEvents: [],
                direction: 1,
                slide: 0,
                activeEvent: 0,
            }
        },
        methods: {
            async fetchEvents() {
                let vm = this;
                this.topEvents.push(...(await listTopEvents()).data);
                this.topEvents.forEach(element => {
                    vm.$set(element, "classes", "")
                });
            },
            changeEvent(dir) {
                let vm = this;
                if(vm.topEvents.length>1){
                  let slideTo = 0;
                  if (dir === 1) {
                    if (this.slide === this.topEvents.length - 1) {
                      slideTo = 0;
                    } else {
                      slideTo = this.slide + 1;
                    }
                    vm.$set(vm.topEvents[this.slide], 'classes', ["slide-transition"])
                    vm.$set(vm.topEvents[this.slide], 'classes', ["slide-transition", "slide-not-active", "slide-left"])
                    vm.$set(vm.topEvents[slideTo], 'classes', ["slide-transition", "slide-right"])
                    vm.$set(vm.topEvents[slideTo], 'classes', ["slide-transition", "active"])
                  } else {
                    if (this.slide === 0) {
                      slideTo = this.topEvents.length - 1;
                    } else {
                      slideTo = this.slide - 1;
                    }
                    vm.$set(vm.topEvents[this.slide], 'classes', ["slide-transition"])
                    vm.$set(vm.topEvents[this.slide], 'classes', ["slide-transition", "slide-not-active", "slide-right"])
                    vm.$set(vm.topEvents[slideTo], 'classes', ["slide-transition", "slide-left"])
                    vm.$set(vm.topEvents[slideTo], 'classes', ["slide-transition", "active"])
                  }
                  vm.slide = slideTo;
                  setTimeout(function () {
                    vm.topEvents.forEach(element => {
                      vm.$set(element, "classes", "")
                    })
                  }, 500);
                }
            },
        },
        computed: {
            topDate() {
                let t = this.$options.filters.fixDateTimezone(this.topEvents[this.slide].startDate.split('T')[0]);
                return t.getDate() + "/" +  parseInt(t.getMonth()+1);
            },
        },
        mounted() {
            let vm = this;
            this.fetchEvents().then(() =>{
              window.setInterval(() => {
                if(vm.topEvents.length>0){
                  vm.changeEvent(1)
                }
              }, 10000)
            });
        }

    }
</script>

<style scoped>

  .banner-section-wrapper {
    width: 100%;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
  }

  .banner-section:before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: .4;
    background-color: black;
  }

  .banner-section {
    width: 100%;
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    display: flex;
    position: absolute;
    top: 0;
    left: 0;
  }

  .banner-section.active {
    z-index: 3;
    opacity: 1;
    transform: translateX(0);
  }

  .banner-section.not-active {
    z-index: -1;
    opacity: 0;
  }

  .banner-wrapper {
    /*padding: 18vh 5% 2vh 5%;*/
    padding: 2vh 5%;
    display: flex;
    margin-top: 20vh;
  }

  .banner-wrapper h1 {
    color: white;
  }

  .banner-wrapper p {
    color: white;
  }

  .banner-side {
    writing-mode: vertical-rl;
    text-orientation: mixed;
    display: flex;
    border-left: 1px white solid;
    padding-left: 0.5em;
  }

  .banner-side p {
    height: 50%;
    margin: 0;
    align-self: flex-end;
    font-family: 'Signal center light';
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 1px;
  }

  .banner-side .banner-location {
    text-align: right;
  }

  .arrows-wrapper {
    display: flex;
    width: 100%;
    margin-top: 3em;
    margin-bottom: 3em;
    align-items: center;
    padding: 0;
  }

  .arrows-wrapper p {
    text-transform: uppercase;
    font-family: "Signal center bold";
    margin: 0;
  }

  .arrow-square {
    height: 75px;
    width: 75px;
    border: 0.5px white solid;
    position: absolute;
    opacity: 0.5;
    cursor: pointer;
  }

  .line {
    flex-grow: 1;
    height: 2px;
    background: white;
    position: relative;
    margin: 20px;
  }

  .arrow-left {
    margin-left: 40px;
  }

  .arrow-right {
    margin-right: 40px;
  }

  .line.arrow-right:after {
    position: absolute;
    content: '';
    bottom: -9px;
    right: -10px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 10px solid white;
  }

  .line.arrow-left:after {
    position: absolute;
    content: '';
    top: -9px;
    left: -10px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-right: 10px solid white;
  }

  .slide-left {
    transform: translateX(100%);
  }

  .slide-right {
    transform: translateX(-100%);
  }

  .slide-transition {
    transition: all 0.5s ease-in-out;
  }

  @media only screen and (max-width: 540px) {
    .banner-side {
      writing-mode: horizontal-tb;
      display: block;
      margin: 0 0.5em;
    }

    .banner-side p {
      display: table-caption;
    }

    .arrows-wrapper {
      width: 90%;
      margin: auto;
    }
  }
</style>
