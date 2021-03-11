<template>
  <header>
    <b-container>
      <b-row>
        <div class="logo-wrapper">
          <img :src="require('../images/logo-ATCL.png')"/>
        </div>
        <b-col>
          <ul :class="['navbar-list',{'active':active}]">
            <router-link tag="li" to="/" exact>Home</router-link>
            <router-link tag="li" to="regulations">Regulations</router-link>
            <li><a href="https://www.rallylebanon.com/" target="_blank">Rally of Lebanon</a></li>
          </ul>
        </b-col>
        <div :class="['hamburger',{'active':active}]" @click="active = !active">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
      </b-row>
    </b-container>
  </header>
</template>

<script>
    export default {
        name: "Header",
        data() {
            return {
                active: false,
            }
        }
    }
</script>

<style scoped>
  header {
    position: absolute;
    z-index: 5;
    width: 100%;
    padding-top: 1vh;
  }

  .navbar-list {
    list-style: none;
    display: flex;
    font-family: 'Signal center bold';
    font-size: larger;
    margin: auto 0;
    float: right;
    color: white;
    padding: 0;
  }

  .navbar-list li {
    margin: 1em;
    padding-bottom: 5px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
  }

  .navbar-list li.router-link-active,
  .navbar-list li:hover {
    border-bottom: 2px white solid;
  }

  .logo-wrapper {
    display: flex;
    margin-left: 15px;
    z-index: 5;
  }

  .logo-wrapper img {
    width: 80px;
    margin: auto;
  }

  .hamburger {
    display: none;
    padding: 1em;
    margin: auto;
  }

  .bar {
    display: block;
    width: 25px;
    height: 3px;
    margin: 5px auto;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
    background-color: #212529;
  }

  @media only screen and (max-width: 768px) {
    header {
      padding: 1vh 0;
      background: rgba(232, 232, 232, 0.9);
      position: fixed;
    }

    .navbar-list {
      position: fixed;
      color: #212529;
      left: -100%;
      top: 12vh;
      flex-direction: column;
      background-color: rgba(232, 232, 232, 0.9);
      width: 100%;
      text-align: center;
      transition: 0.3s;
    }

    .navbar-list.active {
      left: 0;
    }

    .navbar-list li {
      margin: 1em auto;
    }

    .navbar-list li.router-link-active,
    .navbar-list li:hover {
      border-bottom: 2px #212529 solid;
    }

    .hamburger {
      display: block;
      cursor: pointer;
    }

    .hamburger.active .bar:nth-child(2) {
      opacity: 0;
    }

    .hamburger.active .bar:nth-child(1) {
      transform: translateY(8px) rotate(45deg);
    }

    .hamburger.active .bar:nth-child(3) {
      transform: translateY(-8px) rotate(-45deg);
    }

  }
</style>
