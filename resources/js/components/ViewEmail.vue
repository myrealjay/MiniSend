<template>
  <div class="main">
    <div class="col-md-12 head" v-if="email">
      <div>
        <h4>From:</h4>
        <h5 v-if="email.sender">
          {{ email.sender.name }} {{ email.sender.email }}
        </h5>
      </div>
      <div>
        <h4>To:</h4>
        <h5 v-if="email.receiver">
          {{ email.receiver.name }} {{ email.receiver.email }}
        </h5>
      </div>
      <div>
        <h2>{{ email.subject }}</h2>
      </div>
      <div>
        <button
          class="btn btn-info btn-xs"
          style="with: 60px"
          v-if="status == 2"
          @click="toggleText()"
        >
          Text
        </button>
        <button
          class="btn btn-success btn-xs"
          style="with: 60px"
          v-if="status == 1"
          @click="toggleHtml()"
        >
          Html
        </button>

        <button
          class="btn btn-default btn-xs"
          style="with: 60px"
          @click="previous()"
        >
          Back
        </button>
      </div>
    </div>
    <div class="email-body" v-html="content"></div>
    <div class="attachments">
      <p>Attachments</p>
      <ul>
        <li v-for="(attachment, i) in attachments" :key="i">
          {{ attachment }}
        </li>
      </ul>
    </div>
  </div>
</template>

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

<script>
export default {
  mounted() {
    axios
      .get(`/api/mails/get-single/${this.$route.params.id}`)
      .then((response) => {
        this.email = response.data.data;
        this.content = this.email.html_content;
        this.attachments = JSON.parse(this.email.attachments);
      })
      .catch((error) => {});
  },
  data() {
    return {
      email: "",
      content: "",
      status: 2,
      attachments: [],
    };
  },
  methods: {
    toggleHtml() {
      this.status = 2;
      this.content = this.email.html_content;
    },
    toggleText() {
      this.status = 1;
      this.content = this.email.text_content;
    },
    previous() {
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped>
.email-body {
  padding: 50px;
  min-height: 300px;
}
.head {
  padding-left: 50px;
}
.main {
  min-height: 520px;
  padding: 50px;
  padding-top: 60px;
}
.attachments {
  padding-left: 50px;
}

@media screen and (max-width: 768px) {
  .email-body {
    padding: 5px;
  }
  .head {
    padding-left: 5px;
  }
  .main {
    padding-top: 80px;
  }
  .attachments {
    padding-left: 5px;
  }
}
</style>