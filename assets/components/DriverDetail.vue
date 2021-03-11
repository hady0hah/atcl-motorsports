<template>
  <div>
    <Header/>
    <ScrollToTop/>
    <TopTitle back-title="Driver"/>
    <section id="driver-details" class="mt-5 mb-5" v-if="driver">
      <b-container>
        <b-row>
          <b-col md="4" v-if="driver.image">
            <div>
              <div class="driver-image"
                   :style="{ backgroundImage: 'url('  + driver.imageFilePath +'/' + driver.image + ')' }"></div>
            </div>
          </b-col>
          <b-col :md="driver.image?'8':'12'">
            <div class="driver-header">
              <div>
                <div class="logos-wrapper">
                  <img src="/images/atcl-logo.png"/>
                  <img src="/images/fia-logo.png"/>
                </div>
                <div v-if="driver.license">
                  <h3>{{ fixDate(driver.license.issuedDate, 1) }}</h3>
                  <div :class="['overall-pill',{'valid':driver.license.status === 'Active'}]">
                    <div>{{ driver.license.status === "Active" ? "Valid" : "Expired" }} License</div>
                  </div>
                </div>
                <div v-else>
                  <div class="overall-pill">
                    <div>No License</div>
                  </div>
                </div>
              </div>
              <h2>{{ driver.firstName }} {{ driver.lastName }}</h2>
            </div>
            <div class="driver-info">
              <ul>
                <li><span>Date of Birth :</span> {{ fixDate(driver.dateOfBirth) }}</li>
                <li><span>Blood Type :</span> {{ driver.bloodType }}</li>
              </ul>
            </div>

            <div class="driver-info license-info" v-for="license in driver.currentLicenses">
              <ul>
                <li><span>Licence No. :</span> {{ license.licenseNumber }}</li>
                <li><span>FIA Med. Std. :</span> {{ fixDate(license.fiaMedStdDate) }}</li>
                <li><span>Corrected Eyesight :</span> {{ license.correctedEyesight ? "Yes" : "No" }}</li>
                <li><span>Licence Grade :</span> {{ license.licenseGrade.gradeLetter }}</li>
                <li><span>Med. Supervision :</span> {{ license.medSupervision ? "Yes" : "No" }}</li>
                <li><span>WADB :</span> {{ license.wadb ? "Yes" : "No" }}</li>
              </ul>
            </div>
          </b-col>
        </b-row>
      </b-container>
    </section>
    <div v-if="error" class="text-center mt-5 mb-5 pt-5 pb-5">
      {{ error }}
    </div>
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
import {showDriver} from "../api/drivers";

export default {
  name: "DriverDetail",
  components: {Header, PagesFooter, TopTitle, ScrollToTop},
  data() {
    return {
      driver: null,
      error: null
    }
  },
  filters: {},
  methods: {
    async fetchDriver(id) {
      return await showDriver(id);
    },
    fixDate(date, year) {
      let t = this.$options.filters.fixDateTimezone(date);
      if (year) {
        return t.getFullYear();
      }
      return t.toLocaleString('default', {month: 'long'}) + " " + t.getDate() + ", " + t.getFullYear();
    },
  },
  mounted() {
    let vm = this;
    let id = this.$route.params.id;
    if (!id) {
      this.$router.replace({name: 'home'})
    } else {
      this.fetchDriver(id).then(driver => {
        vm.driver = driver
        console.log(vm.driver)
        if (vm.driver.currentLicenses.length > 0) {
          vm.driver.license = vm.driver.currentLicenses[0]
        }
      }).catch(error => {
        vm.error = error.response.data.errors
      });
    }
  }
}
</script>

<style scoped>
:root {
  --green: #28a745;
}

.event-image img {
  width: 100%;
}

.top-title-section {
  background-image: linear-gradient(white, white),
  url("../images/driver-banner.jpg");
}

.driver-header > div {
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 15px;
}

.driver-header > div img {
  height: 100%;
  max-height: 100px;
}

.driver-header h3 {
  text-align: center;
  font-weight: 700;
  margin-bottom: 0;
}

.driver-header h2 {
  border-bottom: 2px solid var(--red);
  width: fit-content;
  padding-bottom: 5px;
}


.name-wrapper {
  padding-bottom: 15px;
  border-bottom: 2px solid var(--red);
}

.driver-image {
  width: 100%;
  padding-top: 120%;
  border: 2px solid black;
  border-radius: 25px;
  background-position: center;
  background-size: cover;
}

.overall-pill {
  margin: 0;
  background-color: var(--red);
  cursor: auto;
}

.overall-pill.valid {
  background-color: var(--green);
}

.overall-pill > div {
  position: relative;
  padding-left: 2.3em;
}

.overall-pill > div:before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  width: 1.53em;
  height: 1.53em;
  border: 3px solid white;
  border-radius: .2em;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, .1), 0 0 0 6px rgba(255, 255, 255, .2);
}

.overall-pill > div:after {
  content: '✕';
  position: absolute;
  top: 0.75em;
  left: 0.4em;
  color: white;
  line-height: 0;
}

.valid.overall-pill > div:after {
  content: '✓';
}

.logos-wrapper {
  height: 100%;
  display: flex;
}

.logos-wrapper img {
  margin-inline-end: 1em;
}

.driver-info {
  padding-inline-start: 1em;
  margin-top: 1em;
}

.driver-info ul {
  list-style: none;
  padding-inline-start: 20px;
  columns: 2;
  -webkit-columns: 2;
  -moz-columns: 2;
}

.driver-info ul li {
  position: relative;
  padding-bottom: 10px;
}

.driver-info ul li:before {
  content: '';
  position: absolute;
  border-right: 2px solid var(--red);
  border-bottom: 2px solid var(--red);
  width: 9px;
  height: 9px;
  top: calc(50% - 5px);
  left: -20px;
  transform: translateY(-50%) rotate(-45deg);
}

.driver-info ul li span {
  font-weight: bold;
}

.license-info {
  border: rgb(231, 231, 231) 2px solid;
  border-radius: 10px;
  padding: 1em;
}

.license-info ul {
  margin-bottom: 0;
}

@media screen and (max-width: 768px) {
  .driver-header {
    margin-top: 1em;
  }

  .driver-info ul {
    margin-top: 1em;
    columns: 1;
    -webkit-columns: 1;
    -moz-columns: 1;
  }
}

@media screen and (max-width: 576px) {
  .driver-header > div {
    flex-direction: column;
    height: auto;
  }
}

</style>
