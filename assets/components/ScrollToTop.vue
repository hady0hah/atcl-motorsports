<template>
  <div class="scroll-top-top" v-show="scY > 100" @click="toTop">
    <i class="fas fa-arrow-up"></i>
  </div>
</template>

<script>
    export default {
        name: "ScrollToTop",
        data() {
            return {
                scTimer: 0,
                scY: 0,
            }
        },
        methods: {
            handleScroll: function () {
                if (this.scTimer) return;
                this.scTimer = setTimeout(() => {
                    this.scY = window.scrollY;
                    clearTimeout(this.scTimer);
                    this.scTimer = 0;
                }, 100);
            },
            toTop: function () {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            },
        },
        mounted() {
            window.addEventListener('scroll', this.handleScroll);
        }
    }
</script>

<style scoped>
  .scroll-top-top {
    position: fixed;
    right: 30px;
    bottom: 30px;
    height: 50px;
    width: 50px;
    text-align: center;
    line-height: 50px;
    font-size: 30px;
    font-family: cursive;
    background: rgb(231, 231, 231);
    color: var(--red);
    border-radius: 16px;
    opacity: 0.7;
    transition: opacity 2ms linear;
    cursor: pointer;
    z-index: 999;
  }

  .scroll-top-top:hover {
    opacity: 1;
  }
</style>
