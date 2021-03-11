<template>
  <div v-if="event">
    <Header/>
    <ScrollToTop/>
    <TopTitle back-title="Event" :above-title="event.championship.name" :main-title="event.label"
              :back-image="event.banner?(event.bannerFilePath +'/' + event.banner):null"/>
    <section id="event-details" class="mt-5 mb-5">
      <b-container>
        <section class="regulations-pills-section mt-5">
          <b-container>
            <b-row>
              <b-col cols="12">
                <ul class="pills-wrapper nav nav-pills" ref="pillsWrapper" @mousedown="pillsMouseDown"
                    @mousemove="pillsMouseMove" @mouseup="pillsScrolling = false" @mouseleave="pillsScrolling = false">
<!--                  <li @click="selectedSection = 1" :class="[{active: selectedSection===1}]"><a>General Info</a></li>-->
<!--                  <li @click="selectedSection = 2" :class="[{active: selectedSection===2}]"><a>Official Notice board</a>-->
<!--                  </li>-->
                  <li @click="selectedSection = 3" :class="[{active: selectedSection===3}]"><a>Results</a></li>
                </ul>
              </b-col>
            </b-row>
          </b-container>
        </section>
<!--        <div v-if="selectedSection === 1">-->
<!--          Where does it come from?-->
<!--          Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin-->
<!--          literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney-->
<!--          College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage,-->
<!--          and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem-->
<!--          Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and-->
<!--          Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the-->
<!--          Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section-->
<!--          1.10.32.-->
<!--          The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections-->
<!--          1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original-->
<!--          form, accompanied by English versions from the 1914 translation by H. Rackham.-->

<!--        </div>-->
<!--        <div class="accordion onb-accordion" role="tablist" v-if="selectedSection === 2">-->
<!--          <b-card no-body class="mb-1" v-for="cat in documentData">-->
<!--            <b-card-header header-tag="header" class="p-1" role="tab">-->
<!--              <b-button block v-b-toggle="cat.label">{{cat.label}}</b-button>-->
<!--            </b-card-header>-->
<!--            <b-collapse :id="cat.label" visible accordion="my-accordion" role="tabpanel">-->
<!--              <b-card-body>-->
<!--                <b-card no-body v-for="document in cat.documents">-->
<!--                  <b-card-header header-tag="header" class="p-1" role="tab">-->
<!--                    <a  class="pdf-name">-->
<!--                      {{ document.label }}</a>-->
<!--                  </b-card-header>-->
<!--                </b-card>-->
<!--              </b-card-body>-->
<!--            </b-collapse>-->
<!--          </b-card>-->
<!--          <b-card no-body class="mb-1" v-for="document in documentData[0].documents">-->
<!--            <b-card-header header-tag="header" class="p-1" role="tab">-->
<!--              <a  class="pdf-name">-->
<!--                {{ document.label }}</a>-->
<!--            </b-card-header>-->
<!--          </b-card>-->
<!--        </div>-->
        <!--        <div class="event-image mb-4">-->
        <!--          <img :src="event.bannerFilePath +'/' + event.banner"/>-->
        <!--        </div>-->
        <!--        <p class="mt-4">{{ event.description }}</p>-->
        <a v-for="document in event.documents" class="pdf-name"
           :href="document.documentPath + '/'+ document.document" target="_blank">{{
          document.document|documentNameFormat }}</a>
        <ResultsTable :Event="event" class="mt-5 mb-5" v-if="event.hasResults && selectedSection === 3"/>
      </b-container>
    </section>
    <footer>
      <PagesFooter/>
    </footer>
  </div>
</template>

<script>
    import Header from "./Header";
    import ScrollToTop from "./ScrollToTop";
    import TopTitle from "./TopTitle";
    import PagesFooter from "./PagesFooter";
    import ResultsTable from "./ResultsTable";

    export default {
        name: "EventDetail",
        components: {ResultsTable, Header, PagesFooter, TopTitle, ScrollToTop},
        props: ["event"],
        data() {
            return {
                pillsScrolling: false,
                pillsStartX: 0,
                pillsScrollLeft: 0,
                selectedSection: 3,
                documentData: [
                    {
                        label: "category1",
                        documents: [
                            {
                                label: "document1"
                            },
                            {
                                label: "document2"
                            },
                            {
                                label: "document3"
                            },
                        ]
                    },
                    {
                        label: "category2",
                        documents: [
                            {
                                label: "document1"
                            },
                            {
                                label: "document2"
                            },
                            {
                                label: "document3"
                            },
                        ]
                    },
                    {
                        label: "category3",
                        documents: [
                            {
                                label: "document1"
                            },
                            {
                                label: "document2"
                            },
                            {
                                label: "document3"
                            },
                        ]
                    },
                ]
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
            }
        },
        filters: {
            documentNameFormat(name) {
                name = name.split("_");
                name.shift();
                return name.join('_')
            }
        },
        mounted() {
            if (!this.event) {
                this.$router.replace({name: 'home'})
            }
        }
    }
</script>

<style scoped>
  .event-image img {
    width: 100%;
  }
  .onb-accordion .btn-secondary {
    border-color: rgb(231, 231, 231);
    background: rgb(231, 231, 231);
    color: var(--red);
    font-weight: 700;
  }
  .onb-accordion .btn-secondary.not-collapsed , .onb-accordion .btn-secondary:focus {
    background-color: #212529;
    color: white;
  }
  .onb-accordion .card-body .card {
    border-radius: 0;
    border-bottom: none;
    border-right: none;
    border-left: none;
    padding-left: 1em;
  }
  .onb-accordion .card .card-header {
    background-color: white;
    border: none;
  }
  .onb-accordion .pdf-name {
    padding: 0.2em;
  }
  .onb-accordion .card-body {
    padding: 0;
  }
</style>
