<template>
  <div>
    <Title title="RESULTS" :info="Event.label"></Title>
    <b-row class="no-gutters">
      <b-col cols="10">
        <button @click="scrollSlider('left')" class="pills-wrapper nav nav-pills scroll-button"><i class="fas fa-arrow-left"></i></button>
        <ul class="pills-wrapper nav nav-pills" ref="pillsWrapper" @mousedown="pillsMouseDown"
            @mousemove="pillsMouseMove" @mouseup="pillsScrolling = false" @mouseleave="pillsScrolling = false">
          <li v-for="(section,index) in sectionResults" @click="showSectionResults(section.id,index)"
              :class="{'active':index === activeSection}">
            {{ section.label }}
          </li>
        </ul>
      </b-col>
      <b-col cols="2" class="text-center overall-pill-wrapper" v-if="Event.resultType!=='timer'">
        <button @click="scrollSlider('right')" class="pills-wrapper nav nav-pills scroll-button"><i class="fas fa-arrow-right"></i></button>
        <div @click="showOverallResults" :class="{'active':!activeSection}">Overall</div>
      </b-col>
    </b-row>
    <table class="results-table" v-if="!resultsLoading">
      <tbody>
      <tr>
        <th style="padding-left: 12px;">Pos.</th>
        <th style="padding-left: 6px;">#</th>
        <th>Car</th>
        <th>Driver<br/>Co-Driver</th>
        <th v-if="Event.resultType==='point'">Point</th>
        <th v-if="Event.resultType!=='point'">Time</th>
        <th>Diff. Prev.<br/>Diff. First</th>
      </tr>
      <tr v-for="(result, index) in results">
        <td>{{ index + 1 }}</td>
        <td>{{ result.participant.number }}</td>
        <td>{{ result.participant.car }}</td>
        <td>
          {{ result.participant.driver.firstName }} {{ result.participant.driver.lastName }}<br/>
          <div v-if="result.participant.coDriver">
          {{ result.participant.coDriver.firstName }} {{ result.participant.coDriver.lastName }}
          </div>
        </td>
        <td v-if="Event.resultType==='point' || Event.resultType==='timer'">
          {{ result.dnf ? 'dnf' : result.value ? result.value : "NA" }}
        </td>
        <td v-if="Event.resultType==='startend' || Event.resultType==='cumulative'">
          {{ result.dnf ? 'dnf' : !result.valueNumber ? 'NA' : (result.formattedResultValue )  }}
        </td>
        <td v-html="diffRes(result,index,Event.resultType)">
        </td>
      </tr>
      </tbody>
    </table>
    <span v-if="!resultsLoading && results.length===0"
          style="font-family: 'Signal center light';font-weight: bold;">No results yet.</span>
    <div class="loader" v-if="resultsLoading"></div>
    <span class="circle" @click="showAllResults" v-if="allResults===0 && !resultsLoading"><i
        class="fas fa-ellipsis-h"></i></span>
  </div>
</template>

<script>
import Title from "./Title";
import {listSectionResults, listEventResults} from "../api/events";

export default {
  name: "ResultsTable",
  props: ['Event'],
  components: {Title},
  data() {
    return {
      resultsLoading: false,
      results: [],
      sectionResults: [],
      allResults: 0,
      activeSection: 0,
    }
  },
  filters: {
    msToTime: function (duration) {
      if (duration > 0) {
        var milliseconds = Math.floor((duration % 1000) / 100),
            seconds = Math.floor((duration / 1000) % 60),
            minutes = Math.floor((duration / (1000 * 60)) % 60),
            hours = Math.floor((duration / (1000 * 60 * 60)) % 24);
        return hours ? hours + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds) + "." + String(milliseconds).padEnd(1, '0') : (minutes ? minutes + ":" + (seconds < 10 ? "0" + seconds : seconds) + "." + String(milliseconds).padEnd(1, '0') : seconds + "." + String(milliseconds).padEnd(1, '0'))

      } else {
        return duration
      }
    }
  },
  methods: {
    diffRes(result, index, resultType) {
      if (result.dnf) return 'dnf'
      if (resultType !== 'cumulative' && (!result.value || index === 0)) return '';
      if (!result.valueNumber || index === 0) return '';
      if (resultType === 'point') {
        return "+" + (this.results[index - 1] - result.value) + '<br/>' + "+" + (this.results[0] - result.value)
      }
      if (resultType === 'startend' || resultType === 'cumulative') {
        return "+" + this.$options.filters.msToTime((result.valueNumber * 1000) - (this.results[index - 1].valueNumber * 1000)) + '<br/>'
            + "+" + this.$options.filters.msToTime((result.valueNumber * 1000) - (this.results[0].valueNumber * 1000))
      }
      return "+" + this.$options.filters.msToTime(this.timeFormatToMs(result.value) - this.timeFormatToMs(this.results[index - 1].value)) + '<br/>'
          + "+" + this.$options.filters.msToTime(this.timeFormatToMs(result.value) - this.timeFormatToMs(this.results[0].value));

    },
    timeFormatToMs(time) {
      time = time.split(':')
      return (3600000 * time[0]) + (60000 * time[1]) + (1000 * time[2]) + parseInt(time[3])
    },
    timeDiff(time1, time2) {
      return (new Date(time2) - new Date(time1));
    },
    getLeafSections(array) {
      let vm = this;
      array.forEach(section => {
        if (section.childrenSections.length > 0) {
          vm.getLeafSections(section.childrenSections);
        } else {
          if (!vm.sectionResults.find(e => e.id === section.id)) {
            if(section.sectionType !== 'tc') {
              vm.sectionResults.push({
                id: section.id,
                label: section.label,
                results: null,
              })
            }
          }
        }
      })
    },
    async fetchSectionResults(sectionId) {
      return await listSectionResults(sectionId);
    },
    async fetchEventResults(eventId) {
      return await listEventResults(eventId);
    },
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
    scrollSlider(direction) {
      const slider = this.$refs.pillsWrapper;
      const scrollAmount = 200;

      if (direction === 'left') {
        slider.scrollLeft -= scrollAmount;
      } else if (direction === 'right') {
        slider.scrollLeft += scrollAmount;
      }
    },
    showAllResults() {
      if (this.activeSection !== -1) {
        this.results = this.sectionResults[this.activeSection].results.sort((x, y) => Number(x.dnf) - Number(y.dnf))
      } else {
        this.results = this.Event.results.sort((x, y) => Number(x.dnf) - Number(y.dnf))
      }
      this.allResults = 1;
    },
    showSectionResults(sectionId, index) {
      let vm = this;
      vm.results = [];
      vm.resultsLoading = true;
      vm.activeSection = index;
      vm.allResults = 1;
      // if (vm.sectionResults[index].results) {
      //   vm.resultsLoading = false;
      //   vm.results = vm.sectionResults[index].results.slice(0, 10);
      //   if (vm.sectionResults[index].results.length > 10) {
      //     vm.allResults = 0;
      //   }
      // } else {
      vm.fetchSectionResults(sectionId).then(results => {
        this.resultsLoading = false;
        vm.sectionResults[vm.activeSection].results = results.data.sort((x, y) => Number(x.dnf) - Number(y.dnf));
        vm.results = vm.sectionResults[vm.activeSection].results.slice(0, 10);
        if (vm.sectionResults[vm.activeSection].results.length > 10) {
          vm.allResults = 0;
        } else {
          vm.allResults = 1;
        }
      });
      // }
    },
    showOverallResults() {
      let vm = this;
      vm.results = [];
      vm.resultsLoading = true;
      vm.allResults = 1;
      vm.activeSection = -1;
      if (vm.Event.results) {
        vm.resultsLoading = false;
        vm.results = vm.Event.results.slice(0, 10);
        if (vm.Event.results.length > 10) {
          vm.allResults = 0;
        }
      } else {
        vm.fetchEventResults(vm.Event.id).then(results => {
          this.resultsLoading = false;
          vm.Event.results = results.data.sort((x, y) => Number(x.dnf) - Number(y.dnf));
          vm.results = vm.Event.results.slice(0, 10);
          if (vm.Event.results.length > 10) {
            vm.allResults = 0;
          }
        });
      }
    },
  },
  mounted() {

    let vm = this;
    if (vm.Event.sections) {
      vm.getLeafSections(vm.Event.sections);
      if (vm.sectionResults.length > 0) {
        vm.showSectionResults(vm.sectionResults[0].id, vm.activeSection);
      } else {
        this.showOverallResults()
      }
    }
  }
}
</script>

<style scoped>

.scroll-button{
  line-height: 50px;
  font-size: 30px;
  font-family: cursive;
  background: rgb(231, 231, 231);
  color: var(--red);
  border-radius: 16px;
  opacity: 0.7;
  transition: opacity 2ms linear;
  cursor: pointer;
}

</style>
