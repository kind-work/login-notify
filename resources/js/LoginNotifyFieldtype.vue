<template>
  <div class="login-notify-list">
    <div class="login-notify-info mb-2" v-if="count > 0">
      <p class="text-xs text-grey-60">You have {{ count }} browser{{ count === 1 ? '' : 's' }} ready to be forgotten. Just hit save.</p>
    </div>

    <div class="login-notify-info mb-2" v-if="browserCount < 1 && count < 1">
      <p class="text-xs text-grey-60">You have no registered browsers at the moment.</p>
    </div>

    <div class="login-notify-list-item pb-4" v-for="(item, key) in data" :key="key">
      <img v-if="item.image" :src="item.image" align="left" />

      <div class="login-notify-list-item-content">
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
        count: 0,
      };
    },
    computed: {
      browserCount() {
        return (Object.keys(this.data) || []).length;
      }
    },
    methods: {
      remove(key) {
        // Remove the data from the data object
        Vue.delete(this.data, key);

        // Increment the count of items to be removed
        this.count++;
      },
    },
    mounted() {
      // Listen for the toast success msg
      this.$events.$on('toast.success', (payload) => {
        // Make sure it comes from saved
        if (payload === 'Saved') {
          // Set the cunt of items to be forgotten to 0
          this.count = 0;
        }
      });
    },
    watch: {
      data(data) {
        this.update(data);
      }
    },
};
</script>

<style lang="scss" scoped>
img {
  height: auto;
  max-width: 100%;
  width: 300px;
}

.login-notify-info {
  display: flex;
  align-items: center;
  padding: 16px;
  background-color: #f5f8fc;
  border-width: 1px;
  border-radius: 3px;
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