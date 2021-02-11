<template>
  <div class="main">
    <div>
      <h4>
        Welcome <b>{{ user.name }}</b>
      </h4>
      <div class="row">
        <div class="col-md-2">
          <button
            id="sendmail"
            class="btn btn-primary btn-xs"
            @click="sendEmail()"
          >
            Send Email
          </button>
        </div>
        <div class="col-md-10">
          <button
            id="api"
            class="btn btn-primary btn-xs"
            @click="show_api_key = !show_api_key"
            v-text="show_api_key ? 'Hide Key' : 'Show Key'"
          ></button>
          <span v-if="show_api_key">{{ company ? company.api_key : "" }}</span>
        </div>
      </div>
    </div>

    <div class="tables">
      <all-emails></all-emails>
    </div>
  </div>
</template>
<script>
import AllEmails from "./datatables/AllEmails";
export default {
  components: { AllEmails },
  data() {
    return {
      show_api_key: false,
    };
  },
  methods: {
    sendEmail() {
      this.$router.push({ name: "send-email" });
    },
  },
  computed: {
    company() {
      let data = JSON.parse(localStorage.getItem("user"));
      return data.company;
    },
    user() {
      let data = JSON.parse(localStorage.getItem("user"));
      return data.company.user;
    },
  },
};
</script>

<style scoped>
.main {
  min-height: 520px;
  padding: 50px;
  padding-top: 60px;
}
.tables {
  padding-top: 30px;
}
</style>