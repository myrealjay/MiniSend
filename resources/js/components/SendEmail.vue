<template>
  <div class="main">
    <div id="contact" class="contact">
      <div class="container-fluid">
        <div class="white_color">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
              <form class="contact_rg">
                <div class="row">
                  <div class="col-md-12">
                    <div class="titlepage">
                      <p style="color: red">{{ error }}</p>
                      <p style="color: green" v-html="success"></p>
                    </div>
                    <div class="col-md-12">
                      <input
                        id="sendername"
                        class="contactus"
                        placeholder="Sender Name"
                        type="text"
                        name="sender Name"
                        v-model="user.sender_name"
                      />
                    </div>
                    <div class="col-md-12">
                      <input
                        id="senderemail"
                        class="contactus"
                        placeholder="Sender Email"
                        type="email"
                        name="Sender Email"
                        v-model="user.sender_email"
                      />
                    </div>

                    <div class="col-md-12">
                      <input
                        id="receivername"
                        class="contactus"
                        placeholder="Receiver Name"
                        type="text"
                        name="Receiver Name"
                        v-model="user.receiver_name"
                      />
                    </div>
                    <div class="col-md-12">
                      <input
                        id="receiveremail"
                        class="contactus"
                        placeholder="Receiver Email"
                        type="email"
                        name="Receiver Email"
                        v-model="user.receiver_email"
                      />
                    </div>

                    <div class="col-md-12">
                      <input
                        id="subject"
                        class="contactus"
                        placeholder="Subject"
                        type="text"
                        name="Subject"
                        v-model="user.subject"
                      />
                    </div>

                    <div class="col-md-12">
                      <textarea
                        id="text_content"
                        class="textarea"
                        placeholder="Text Contnent"
                        type="text"
                        name="Text Content"
                        v-model="user.text_content"
                        rows="4"
                      ></textarea>
                    </div>

                    <div class="col-md-12">
                      <tinymce
                        id="html_content"
                        class="form-control"
                        v-model="user.html_content"
                      ></tinymce>
                      <!-- <textarea
                        class="textarea"
                        placeholder="Html Contnent"
                        type="text"
                        name="Html Content"
                        v-model="user.html_content"
                        rows="6"
                      ></textarea> -->
                    </div>

                    <div class="col-md-12">
                      <input
                        id="file"
                        type="file"
                        multiple
                        @change="fileSelected"
                      />
                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                      <p style="color: red">{{ error }}</p>
                      <p style="color: green" v-html="success"></p>
                      <button
                        id="send"
                        class="send"
                        @click.prevent="send()"
                        v-html="sending ? 'Wait ..' : 'Send'"
                      ></button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import tinymce from "./tinymice";
export default {
  components: { tinymce },
  data() {
    return {
      error: "",
      success: "",
      user: {},
      sending: false,
      files: [],
    };
  },
  methods: {
    fileSelected(e) {
      this.files = e.target.files;
    },
    send() {
      if (
        !(
          this.user.sender_name &&
          this.user.sender_email &&
          this.user.receiver_name &&
          this.user.receiver_email &&
          this.user.subject
        )
      ) {
        this.error = "Only html content or text content can be empty";
      } else {
        this.sending = true;
        let formData = new FormData();
        formData.append("from_email", this.user.sender_email);
        formData.append("from_name", this.user.sender_name);
        formData.append("to_email", this.user.receiver_email);
        formData.append("to_name", this.user.receiver_name);
        formData.append("subject", this.user.subject);
        formData.append("text_content", this.user.text_content);
        formData.append("html_content", this.user.html_content);
        for (let i = 0; i < this.files.length; i++) {
          formData.append("attachments[]", this.files[i]);
          console.log();
        }

        axios
          .post("/api/mails/send", formData)
          .then((response) => {
            this.success = response.data.message;
            this.user = {};
          })
          .catch((error) => {
            this.error.response.data.message;
          })
          .finally(() => {
            this.sending = false;
          });
      }
    },
  },
};
</script>
<style scoped>
.main {
  min-height: 520px;
  padding: 100px;
  padding-top: 60px;
}
</style>