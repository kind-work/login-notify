<template>
  <div class="login-notify-list">
   <p v-if="data.length < 1">You have no registered browsers at the moment.</p> 

    <div class="login-notify-list-item pb-4" v-for="(item, key) in data" :key="key">
      <img v-if="item.image" :src="item.image" align="left" />

      <div class="login-notify-list-item-content">
        <h2>{{ item.app }}</h2>

        <p>
          <strong>Location:</strong> {{ item.cityName }}, {{ item.regionName }}, {{ item.countryName }}<br />
          <strong>Browser:</strong> {{ item.browser }}<br />
          <strong>OS:</strong> {{ item.os }}<br />
          <strong>IP:</strong> {{ item.ip }}<br />
          <strong>Login Time:</strong> {{ item.at }}<br />
          <button class="mt-2 btn btn-danger" @click="remove(key)">Forget Browser</button>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    mixins: [Fieldtype],
    data() {
      return {
        data: this.value,
      };
    },
    methods: {
      remove(key) {
        Vue.delete(this.data, key);
      },
    },
};
</script>

<style lang="scss" scoped>
img {
  height: auto;
  max-width: 100%;
  width: 300px;
}

@media (max-width: 767px) {
  .login-notify-list-item-content {
    clear: both;
    padding-top: 1rem;
  }
}

@media (min-width: 768px) {
  img {
    float: left;
    height: auto;
    max-width: 40%;
    padding-right: 1rem;
  }

  .login-notify-list-item-content {
    float: left;
    width: 60%;
  }

  .login-notify-list-item:after {
    content: "";
    display: table;
    clear: both;
  }
}
</style>