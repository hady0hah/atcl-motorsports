<template>
  <div>
    <Header/>
    <ScrollToTop/>
    <TopTitle above-title="Sporting Regulations" main-title="Regulations"/>
    <section class="regulations-pills-section mt-5">
      <b-container>
        <b-row>
          <b-col cols="12">
            <ul class="pills-wrapper nav nav-pills" ref="pillsWrapper" @mousedown="pillsMouseDown"
                @mousemove="pillsMouseMove" @mouseup="pillsScrolling = false" @mouseleave="pillsScrolling = false">
              <li v-for="category in categories"><a :href="'#'+category.name">{{ category.label }}</a></li>
            </ul>
          </b-col>
        </b-row>
      </b-container>
    </section>
    <div class="loader" v-if="categoriesLoading"></div>
    <div v-else>
      <section :id="category.name" class="regulations-section" v-for="category in categories">
        <b-container>
          <b-row>
            <b-col cols="12" class="regulations-title">
              <h2>
                {{ category.label }}
              </h2>
            </b-col>
            <b-col cols="12">
              <ul class="documents-list">
                <li v-for="document in category.documents">
                  <a :href="document.documentPath + '/'+ document.document " target="_blank">
                    <img :src="require('../images/pdf-download.png')"/>
                    {{ document.label }}</a>
                </li>
              </ul>
            </b-col>
          </b-row>
        </b-container>
      </section>
    </div>
    <footer style="margin-top: 2em">
      <PagesFooter/>
    </footer>
  </div>
</template>

<script>
import Title from "./Title";
import TopTitle from "./TopTitle";
import Header from "./Header";
import ScrollToTop from "./ScrollToTop";
import PagesFooter from "./PagesFooter";
import {listDocumentCategories} from "../api/documents";

export default {
  name: "Regulations",
  components: {PagesFooter, ScrollToTop, Header, TopTitle, Title},
  data() {
    return {
      pillsScrolling: false,
      pillsStartX: 0,
      pillsScrollLeft: 0,
      categoriesLoading: false,
      categories: [],
      sections: ["General", "Speed Test", "Rotax Max", "Spring Rally", "Rock Crawling"]
      // , "Hill Climb", "Drift", "Competitor Installation"]
    }
  },
  methods: {
    pillsMouseDown(e) {
      let slider = this.$refs.pillsWrapper;
      this.pillsScrolling = true;
      this.pillsStartX = e.pageX - slider.offsetLeft;
      this.pillsScrollLeft = slider.scrollLeft;
    },
    pillsMouseMove(e) {
      if (!this.pillsScrolling) return;
      let slider = this.$refs.pillsWrapper;
      const x = e.pageX - slider.offsetLeft;
      const walk = x - this.pillsStartX;
      slider.scrollLeft = this.pillsScrollLeft - walk;
    },
    async fetchDocumentCategoriesList() {
      let vm = this;
      vm.categoriesLoading = true;
      await listDocumentCategories().then(result => {
        vm.categoriesLoading = false;
        vm.categories = result.data;
      })
    },
  },
  mounted() {
    let vm = this;
    this.fetchDocumentCategoriesList().then(() => {
    });
  }
}
</script>

<style scoped>
.regulations-title {
  margin-top: 2em;
  text-align: center;
}

.documents-list a {
  font-weight: 600;
  font-size: 20px;
  letter-spacing: 0.64px;
}

.documents-list li {
  display: flex;
  margin: 1em auto;
  align-items: center;
  padding: 10px 30px 10px 10px;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 5px 7px -1px rgba(51, 51, 51, 0.23);
  cursor: pointer;
  background-color: #fff;
  animation: fadeIn 1s linear;
  animation-fill-mode: both;
}

.documents-list li img {
  max-width: 100%;
  width: 60px;
  margin-right: 25px;
  border-radius: 50%;
}

@-webkit-keyframes fadeIn {
  0% {
    opacity: 0;
    top: -500px;
  }
  75% {
    opacity: 0.5;
    top: 0px;
  }
  100% {
    opacity: 1;
  }
}

/*.documents-list li:before {*/
/*  background-image: url('../images/pdf-download.png');*/
/*  background-size: 10px 20px;*/
/*  display: inline-block;*/
/*  width: 10px;*/
/*  height: 20px;*/
/*  content:"";*/
/*}*/
</style>
