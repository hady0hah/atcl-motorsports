<template>
  <div>
    <Header/>
    <ScrollToTop/>
    <TopBanner :top-events="upcomingEventsList"/>
    <section class="upcoming-event-section">
      <b-container>
        <ResultsTable v-if="runningEvent" :Event="runningEvent" style="margin-bottom: 4em;"/>
        <div v-else-if="upcomingEvent">
          <Title title="Upcoming event" :info="upcomingEvent.label"></Title>
          <b-row class="timer-wrapper">
            <TimerItem v-for="time in getTimeRemaining" :item="time.text" :value="time.time" :key="'timer-'+time.id"/>
          </b-row>
        </div>
      </b-container>
    </section>
    <section class="main-section">
      <b-container>
        <b-row>
          <b-col lg="6" class="feeds-section">
            <b-col cols="12" class="section-title">
              <h2>
                Latest news
              </h2>
            </b-col>
            <div class="feeds-wrapper" v-if="newsList">
              <div class="row news-wrapper" v-for="news in newsList" :key="news.id">
                <div :class="news.event ? 'col-8' : 'col-12'"><h6 v-if="news.event">{{ news.event.label }}</h6>
                  <a class="pdf-name" :href="news.documentPath + '/'+ news.document" target="_blank">
                    {{ news.document | documentNameFormat }}
                  </a>
                  <p class="news-date">{{ newsDate(news.createdAt) }}</p></div>
                <div class="col-4" v-if="news.event">
                  <div class="image-wrapper">
                    <div
                        :style="'background-image: url(\''+news.event.bannerFilePath + '/'+news.event.banner+'\');'"></div>
                  </div>
                </div>
              </div>
              <div class="text-center mb-4" v-if="newsList.length>0">
                                <span :class="['dot',{'active':newsPage===n}]" v-for="(n,index) in 2"
                                      @click="newsPage=n"></span>
              </div>
              <div v-if="newsList.length===0"
                   style="font-family: 'Signal center light';font-weight: bold;">
                No news yet.
              </div>
            </div>

            <div class="loader" v-else></div>
            <!--            <b-pagination v-model="upcomingEventsPage" :total-rows="upcomingEventsCount" per-page="4" align="center"-->
            <!--                          size="sm" @page-click="eventPageChange" class="events-pagination"></b-pagination>-->
          </b-col>
          <b-col lg="6" class="upcoming-events-section">
            <b-col cols="12" class="section-title">
              <h2>
                Upcoming events
              </h2>
            </b-col>
            <b-col cols="12" class="mb-4">
              <div class="event-month">
                <div class="text-left" @click="eventPageChange(-1)"><i class="fas fa-arrow-left"></i>
                </div>
                <div>{{ newsMonth(activeMonth) }}</div>
                <div class="text-right" @click="eventPageChange(1)"><i class="fas fa-arrow-right"></i>
                </div>
              </div>
              <div class="loader" v-if="eventsLoading"></div>
              <ul class="events-list" v-if="upcomingEventsList.length>0">
                <li class="row" v-for="event in upcomingEventsList" @click="setActiveEvent(event)">
                  <h6 class="col-1 event-day">{{ newsDay(event.startDate) }}</h6>
                  <div class="d-flex flex-column col-11">
                    <div class="event-championship">{{ event.championship.name }}</div>
                    <div class="event-name">{{ event.label }}</div>
                  </div>
                </li>
              </ul>
              <div v-else style="font-family: 'Signal center light';font-weight: bold;">No events this
                month.
              </div>
            </b-col>
            <div class="running-event-wrapper" v-if="activeEvent">
              <div class="running-event-image">
                  <img v-if="activeEvent.image" :src="activeEvent.bannerFilePath + '/' + activeEvent.image"/>
                  <img v-else :src="activeEvent.bannerFilePath + '/' + activeEvent.banner"/>
              </div>
              <h6>{{ activeEvent.championship.name }}</h6>
              <h2>{{ activeEvent.label }}</h2>
              <p>{{ newsDate(activeEvent.startDate) }}</p>
              <div v-if="activeEvent.hasResults">
                <div class="loader" v-if="!activeEventResults"/>
                <table class="results-table" v-else>
                  <tbody>
                  <tr>
                    <th style="padding-left: 12px;">Pos.</th>
                    <th style="padding-left: 6px;">#</th>
                    <th>Car</th>
                    <th>Driver<br/>Co-Driver</th>
                    <th v-if="activeEvent.resultType==='point'">Point</th>
                    <th v-if="activeEvent.resultType!=='point'">Time</th>
                    <th>Diff. Prev.<br/>Diff. First</th>
                  </tr>
                  <tr v-for="(result, index) in activeEventResults">
                    <td>{{ index + 1 }}</td>
                    <td>{{ result.participant.number }}</td>
                    <td>{{ result.participant.car }}</td>
                    <td>
                      {{ result.participant.driver.firstName }} {{ result.participant.driver.lastName }}<br/>
                      <div v-if="result.participant.coDriver">
                      {{ result.participant.coDriver.firstName }} {{ result.participant.coDriver.lastName }}
                      </div>
                    </td>
                    <td v-if="activeEvent.resultType==='point' || activeEvent.resultType==='timer'">
                      {{ result.dnf ? 'dnf' : result.value ? result.value : "NA" }}
                    </td>
                    <td v-if="activeEvent.resultType==='startend' || activeEvent.resultType==='cumulative'">
                      {{ result.dnf ? 'dnf' : !result.valueNumber ? 'NA' : (result.valueNumber * 1000) | msToTime }}
                    </td>
                    <td v-html="diffRes(result,index,activeEvent.resultType)">
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <a v-for="document in activeEvent.documents" class="pdf-name" v-else
                 :href="document.documentPath + '/'+ document.document" target="_blank">
                {{ document.document|documentNameFormat }}
              </a>
              <router-link tag="span" :to="{ name: 'event', params: { event: activeEvent  }}"
                           class="circle"><i class="fas fa-ellipsis-h"></i></router-link>
            </div>
          </b-col>
        </b-row>
      </b-container>
    </section>
    <section class="sponsors-section">
      <b-container>
        <b-row>
          <b-row>
            <b-col sm="6" lg="3" class="sponsor">
              <img :src="require('../images/commercial-logo.png')">
            </b-col>
            <b-col sm="6" lg="3" class="sponsor">
              <img :src="require('../images/domtech-logo.png')">
            </b-col>
            <b-col sm="6" lg="3" class="sponsor">
              <img :src="require('../images/Linglong-Tires-logo.png')">
            </b-col>
            <b-col sm="6" lg="3" class="sponsor">
              <img :src="require('../images/karma-heights.png')">
            </b-col>
            <b-col sm="6" lg="3" class="sponsor">
              <img :src="require('../images/oblogo_white.png')">
            </b-col>
          </b-row>

          <b-row>
            <b-col sm="5" lg="2" class="sponsor">
              <img :src="require('../images/coral-logo.png')">
            </b-col>
            <b-col sm="6" lg="3" class="sponsor">
              <img :src="require('../images/jounieh-municipality.png')">
            </b-col>
            <b-col sm="6" lg="3" class="sponsor">
              <img :src="require('../images/motorsport-logo.png')">
            </b-col>
            <b-col sm="1" lg="1" class="sponsor">
              <img :src="require('../images/LBCI.jpg')">
            </b-col>
          </b-row>
        </b-row>
      </b-container>
    </section>
    <footer>
      <PagesFooter/>
    </footer>
  </div>
</template>

<script>
import TopBanner from "./TopBanner";
import Title from "./Title";
import TimerItem from "./TimerItem";
import NewsItem from "./NewsItem";
import PagesFooter from "./PagesFooter";
import {
  listUpcomingEvents,
  showUpcomingEvent,
  showRunningEvent,
  listNewsFeeds, listEventResults,
} from '../api/events';
import Header from "./Header";
import ResultsTable from "./ResultsTable";
import ScrollToTop from "./ScrollToTop";

export default {
  name: 'Home',
  components: {ScrollToTop, ResultsTable, Header, PagesFooter, NewsItem, TimerItem, Title, TopBanner},
  props: {},
  data() {
    return {
      pillsScrolling: false,
      eventsLoading: false,
      pillsStartX: 0,
      pillsScrollLeft: 0,
      upcomingEventsPage: 1,
      dateNow: Date.now(),
      upcomingEvent: {},
      upcomingEventsList: [],
      yearlyEventsList: [],
      activeEvent: 0,
      activeEventResults: false,
      runningEvent: null,
      activeMonth: null,
      documentsList: null,
      newsPage: 1,
      timer: [
        {id: 0, text: "Days", time: 1},
        {id: 1, text: "Hours", time: 1},
        {id: 2, text: "Minutes", time: 1},
        {id: 3, text: "Seconds", time: 1}
      ],
    }
  },
  filters: {
    documentNameFormat(name) {
      name = name.split("_");
      name.shift();
      return name.join('_')
    },
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
        return "+" + (this.activeEventResults[index - 1].value - result.value) + '<br/>' + "+" + (this.activeEventResults[0].value - result.value)
      }
      if (resultType === 'startend' || resultType === 'cumulative') {
        return "+" + this.$options.filters.msToTime((result.valueNumber * 1000) - (this.activeEventResults[index - 1].valueNumber * 1000)) + '<br/>'
            + "+" + this.$options.filters.msToTime((result.valueNumber * 1000) - (this.activeEventResults[0].valueNumber * 1000))
      }
      return "+" + this.$options.filters.msToTime(this.timeFormatToMs(result.value) - this.timeFormatToMs(this.activeEventResults[index - 1].value)) + '<br/>'
          + "+" + this.$options.filters.msToTime(this.timeFormatToMs(result.value) - this.timeFormatToMs(this.activeEventResults[0].value));

    },
    timeFormatToMs(time) {
      time = time.split(':')
      return (3600000 * time[0]) + (60000 * time[1]) + (1000 * time[2]) + parseInt(time[3])
    },
    timeDiff(time1, time2) {
      return (new Date(time2) - new Date(time1));
    },
    newsMonth(date) {
      let t = new Date(date);
      return t.toLocaleString('default', {month: 'long'}) + " " + t.getFullYear();
    },
    newsDay(date) {
      let t = new Date(date.split('T')[0]);
      return t.getDate();
    },
    newsDate(date) {
      let t = this.$options.filters.fixDateTimezone(date);
      return t.toLocaleString('default', {month: 'long'}) + " " + t.getDate() + ", " + t.getFullYear();
    },
    setActiveEvent(Event) {
      let vm = this;
      vm.activeEvent = Event;
      vm.activeEventResults = false;
      vm.fetchEventResults(vm.activeEvent.id).then(results => {
        vm.activeEventResults = results.data.sort((x, y) => Number(x.dnf) - Number(y.dnf));
      });
    },
    eventPageChange(direction) {
      this.activeMonth = new Date(this.activeMonth.setMonth(this.activeMonth.getMonth() + direction));
      this.fetchUpcomingEventsList();
    },
    formatDate(date) {
      var d = new Date(date),
          month = '' + (d.getMonth() + 1),
          day = '' + d.getDate(),
          year = d.getFullYear();

      if (month.length < 2)
        month = '0' + month;
      if (day.length < 2)
        day = '0' + day;

      return [year, month, day].join('-');
    },
    async fetchNewsFeeds() {
      let vm = this;
      await listNewsFeeds().then(result => {
        vm.documentsList = result.data;
      }).catch(e => {
        vm.documentsList = []
        console.log(e)
      })
    },
    async fetchUpcomingEventsList() {
      let vm = this;
      vm.eventsLoading = true;
      var firstDate = vm.formatDate(new Date(vm.activeMonth.getFullYear(), vm.activeMonth.getMonth(), 1));
      var lastDate = vm.formatDate(new Date(vm.activeMonth.getFullYear(), vm.activeMonth.getMonth() + 1, 0));
      await listUpcomingEvents(firstDate, lastDate).then(result => {
        vm.eventsLoading = false;
        vm.upcomingEventsList = result.data;
      })
    },
    async fetchUpcomingEvent() {
      return await showUpcomingEvent();
    },
    async fetchRunningEvent() {
      return await showRunningEvent();
    },
    async fetchEventResults(eventId) {
      return await listEventResults(eventId);
    },
  },
  computed: {
    newsList() {
      return this.documentsList ? this.documentsList.slice((this.newsPage - 1) * 5, this.newsPage * 5) : null;
    },
    getTimeRemaining: function () {
      let t = Date.parse(this.$options.filters.fixDateTimezone(this.upcomingEvent.startDate)) - this.dateNow;
      if (t >= 0) {
        this.timer[3].time = Math.floor(t / 1000 % 60); //seconds
        this.timer[2].time = Math.floor(t / 1000 / 60 % 60); //minutes
        this.timer[1].time = Math.floor(t / (1000 * 60 * 60) % 24); //hours
        this.timer[0].time = Math.floor(t / (1000 * 60 * 60 * 24)); //days
      } else {
        this.timer[3].time = this.timer[2].time = this.timer[1].time = this.timer[0].time = 0;
        this.upcomingEvent = null;
      }

      setTimeout(() => {
        this.dateNow = Date.now();
      }, 1000)
      return this.timer
    },
  },
  mounted() {
    let vm = this;
    vm.activeMonth = new Date();
    this.fetchUpcomingEventsList().then(() => {
      if (vm.upcomingEventsList.length > 0) {
        vm.setActiveEvent(vm.upcomingEventsList[0]);
      }
    });
    this.fetchNewsFeeds();
    this.fetchRunningEvent().then(event => {
      if (event.data === null) {
        vm.fetchUpcomingEvent().then(event => {
          if (event) {
            vm.upcomingEvent = event.data;
            vm.isUpcomingEvent = 1;
          }

        });
      } else {
        vm.runningEvent = event.data;
      }
    });
  }
}
</script>

<style scoped>

.news-wrapper {
  margin-bottom: 2em;
}

.image-wrapper {
  position: relative;
  display: flex;
  border: 2px solid var(--red);
  width: 100%;
  padding-top: 100%;
}

.image-wrapper div {
  z-index: 1;
  position: absolute;
  top: 10%;
  left: 10%;
  bottom: 10%;
  right: 10%;
  background-position: center;
  background-size: cover;
}

.image-wrapper::before {
  content: "";
  position: absolute;
  height: calc(100% + 10px);
  width: 50%;
  background-color: white;
  top: -5px;
  left: 25%;
}

.image-wrapper::after {
  content: "";
  position: absolute;
  height: 50%;
  width: calc(100% + 10px);
  background-color: white;
  top: 25%;
  left: -5px;
}

.section-title {
  margin-bottom: 2em;
  text-align: center;
}

.feeds-wrapper {
  margin-right: 5%;
}

.event-month {
  background-color: #212529;
  color: white;
  border: none;
  border-radius: 0.25rem;
  font-family: 'Signal center light';
  font-weight: 700;
  display: grid;
  grid-auto-flow: column;
  text-align: center;
  padding: 0.5em 1.5em;
  cursor: pointer;
}

.events-list {
  padding: 0;
  list-style-type: none;
}

.events-list li {
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

.events-list .event-day {
  margin-bottom: 0;
}

.events-list .event-championship {
  color: var(--red);
  font-family: "Signal center light";
}

.events-list .event-name {
  font-size: larger;
  font-family: "Signal center bold";
}

.running-event-wrapper {
  margin-bottom: 2em;
}

.upcoming-event-section {
  margin: 4em auto 2em;
}

.running-event-image {
  margin-bottom: 1em;
}

.running-event-image img {
  width: 100%;
}

.timer-wrapper {
  margin: 2em auto;
}

.sponsors-section {
  background-image: url("../images/Atcl-Banner.jpg");
  position: relative;
  background-attachment: fixed;
  background-position: top;
  background-repeat: no-repeat;
  background-size: cover;
  margin-bottom: 4em;
  padding: 2em 0;
}

.sponsors-section div {
  height: 100%;
  justify-content: center;
}

.sponsors-section:before {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.dot {
  height: 15px;
  width: 15px;
  background-color: rgb(231, 231, 231);
  margin-right: 0.5em;
  border-radius: 50%;
  display: inline-block;
  cursor: pointer;
}

.dot.active {
  background-color: rgb(203, 203, 203);
}

.sponsor {
  display: flex;
  min-height: 100px;
  margin: 1em auto;
}

.sponsor img {
  width: 100%;
  margin: auto;
}

@media only screen and (max-width: 991px) {
  .sponsor {
    margin: 1em auto;
  }
}
</style>
