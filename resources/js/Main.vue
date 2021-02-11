<template>
  <div>
    <!-- loader  -->
    <div class="loader_bg">
      <div class="loader"><img src="images/loading.gif" alt="#" /></div>
    </div>
    <!-- end loader -->
    <!-- header -->
    <header>
      <!-- header inner -->
      <div class="header-top">
        <div class="header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-xl-2 col-lg-4 col-md-4 col-sm-3 col logo_section">
                <div class="full">
                  <div class="center-desk">
                    <div class="logo">
                      <a href="index.html"
                        ><img src="images/minisend.png" alt="#"
                      /></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-10 col-lg-8 col-md-8 col-sm-9">
                <div class="header_information">
                  <div class="menu-area">
                    <div class="limit-box">
                      <nav class="main-menu">
                        <ul class="menu-area-main">
                          <li class="active"><a href="/">Home</a></li>
                          <li><router-link to="/dashboard" >Dashboard</router-link></li>
                        </ul>
                      </nav>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end header inner -->
      </div>
    </header>

   <router-view></router-view>

    <footer>
      <div class="footer">
        <div class="container"></div>
        <div class="copyright">
          <div class="container">
            <p>
              Nwachukwu James @RealJay </a>
            </p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>
 
<script>
import "vue-select/dist/vue-select.css";

export default {};
</script>
