<template>
  <router-link tag="b-row" :to="{ name: 'event', params: { event: news  }}" class="news-wrapper">
    <b-col cols="8">
      <h6>{{ news.championship.name }}</h6>
      <h2>{{ news.label }}</h2>
<!--      <p class="news-content">{{ news.description }}</p>-->
      <p class="news-date">{{ newsDate(news.startDate) }}</p>
    </b-col>
    <b-col cols="4">
      <div class="image-wrapper">
<!--        <div :style="{ backgroundImage: 'url(' + require('../images/'+news.image) + ')' }"></div>-->
        <div :style="{ backgroundImage: 'url(' + news.bannerFilePath +'/' + news.banner + ')' }"></div>
      </div>
    </b-col>
  </router-link>
</template>

<script>
    export default {
        name: "NewsItem",
        props: ["news"],
        methods: {
            newsDate(date){
                let t = this.$options.filters.fixDateTimezone(date);
                return t.toLocaleString('default', { month: 'long' }) + " "+ t.getDate() + ", " + t.getFullYear();
            },
        },
    }
</script>

<style scoped>
  .news-wrapper {
    margin-bottom: 2em;
    cursor: pointer;
  }
  .news-wrapper .news-content {
    color: #a3a3a3;
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
  }
  .news-wrapper .news-date {
    font-family: 'Signal center light';
    font-weight: bold;
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
</style>
