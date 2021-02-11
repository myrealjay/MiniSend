<template>
  <div>
    <!-- about  -->
    <div id="about" class="about">
      <div class="container">
        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="about-box">
              <h2>Mini<strong class="yellow">Send</strong></h2>
              <p>Send Transactional emails from your application at ease.</p>
              <a href="#contact">Try It</a>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="about-box">
              <figure><img src="images/email-logo.jpg" alt="#" /></figure>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end abouts -->

    <!-- contact -->
    <div id="contact" class="contact">
      <div class="container-fluid padding_left2">
        <div class="white_color">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
              <div>
                <form class="contact_bg">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="titlepage">
                        <p>Already have an account? Login</p>
                        <p style="color: red">{{ loginerror }}</p>
                      </div>
                      <div class="col-md-12">
                        <input
                          class="contactus"
                          placeholder="Your Email"
                          type="email"
                          name="Your Name"
                          v-model="login.email"
                        />
                      </div>
                      <div class="col-md-12">
                        <input
                          class="contactus"
                          placeholder="Your Password"
                          type="password"
                          name="Your Password"
                          v-model="login.password"
                        />
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <button
                          class="send"
                          @click.prevent="Signin()"
                          v-html="sending ? 'Wait ..' : 'Login'"
                        ></button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
              <form class="contact_bg">
                <div class="row">
                  <div class="col-md-12">
                    <div class="titlepage">
                      <h2>Join <strong class="yellow">us</strong></h2>
                      <p style="color: red">{{ error }}</p>
                      <p style="color: green" v-html="success"></p>
                    </div>
                    <div class="col-md-12">
                      <input
                        class="contactus"
                        placeholder="Your Name"
                        type="text"
                        name="Your Name"
                        v-model="user.user_name"
                      />
                      <span style="color: red" v-if="errorData">{{
                        errorData.user_name ? errorData.user_name[0] : ""
                      }}</span>
                    </div>
                    <div class="col-md-12">
                      <input
                        class="contactus"
                        placeholder="Your Email"
                        type="text"
                        name="Your Email"
                        v-model="user.email"
                      />
                      <span style="color: red" v-if="errorData">{{
                        errorData.email ? errorData.email[0] : ""
                      }}</span>
                    </div>
                    <div class="col-md-12">
                      <input
                        class="contactus"
                        placeholder="Your Organization"
                        type="text"
                        name="Your Organization"
                        v-model="user.company_name"
                      />
                      <span style="color: red" v-if="errorData">{{
                        errorData.company ? errorData.company[0] : ""
                      }}</span>
                    </div>
                    <div class="col-md-12">
                      <input
                        class="contactus"
                        placeholder="Your Password"
                        type="password"
                        name="Your Password"
                        v-model="user.password"
                      />
                      <span style="color: red" v-if="errorData">{{
                        errorData.password ? errorData.password[0] : ""
                      }}</span>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                      <button
                        class="send"
                        @click.prevent="Register()"
                        v-html="sending ? 'Wait ..' : 'Register'"
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
export default {
  data() {
    return {
      user: {
        user_name: "",
        email: "",
        company_name: "",
        password: "",
      },
      error: "",
      loginerror: "",
      login: {
        email: "",
        password: "",
      },
      success: "",
      sending: false,
      errorData: {},
      Register() {
        if (
          !(
            this.user.user_name &&
            this.user.email &&
            this.user.company_name &&
            this.user.password
          )
        ) {
          this.error = "Kindly fill out all fields";
        } else {
          this.sending = true;
          axios
            .post("/api/companies/register", this.user)
            .then((response) => {
              this.success = "Registration was successful, Kindly login";
              this.errorData = {};
              this.error = "";
            })
            .catch((error) => {
              let data = error.response.data.data;
              this.errorData = data;
              console.log(this.errorData);
              this.error = error.response.data.message;
            })
            .finally(() => {
              this.sending = false;
            });
        }
      },
      Signin() {
        if (!(this.login.email && this.login.password)) {
          this.loginerror = "Kindly fillout all fields";
        } else {
          this.sending = true;
          axios
            .post("/api/auth/login", this.login)
            .then((response) => {
              let data = response.data;
              localStorage.setItem("user", JSON.stringify(data));
              window.location = "/dashboard";
            })
            .catch((error) => {
              this.loginerror = error.response.data.message;
            })
            .finally(() => {
              this.sending = false;
            });
        }
      },
    };
  },
};
</script>