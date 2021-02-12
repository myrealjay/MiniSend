<template>
  <div class="projects">
    <div :class="{ spinner: show }"></div>
    <div class="tableFilters">
      <div
        class="form-group form-float"
        style="display: flex; justify-content: space-between"
      >
        <div>
          <label for="">Status</label>
          <v-select
            :options="['posted', 'sent', 'failed']"
            @input="getProjects()"
            v-model="tableData.status"
          ></v-select>
        </div>
        <div>
          <label for="">SUbjects</label>
          <v-select
            :options="subjects"
            @input="getProjects()"
            v-model="tableData.subject"
          ></v-select>
        </div>
        <div>
          <label for="">Recipients</label>
          <v-select
            :options="receivers"
            label="name"
            @input="getProjects()"
            v-model="tableData.receiver"
          ></v-select>
        </div>
        <div>
          <label for="">Senders</label>
          <v-select
            :options="senders"
            label="name"
            @input="getProjects()"
            v-model="tableData.sender"
          ></v-select>
        </div>
        <div class="control">
          <div>
            <label for="">Length</label>
            <v-select
              :options="options"
              @input="getProjects()"
              v-model="tableData.length"
            ></v-select>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <datatable
        :columns="columns"
        :sortKey="sortKey"
        :sortOrders="sortOrders"
        @sort="sortBy"
      >
        <tbody v-if="emails.length > 0">
          <tr v-for="(email, i) in emails" :key="email.id">
            <td>
              {{ getSender(email.sender) }}
            </td>
            <td>
              {{ getSender(email.receiver) }}
            </td>
            <td>{{ email.subject }}</td>
            <td>
              <div
                class="btn btn-info btn-xs"
                style="width: 80px"
                v-if="email.status == 'posted'"
              >
                Posted
              </div>
              <div
                class="btn btn-success btn-xs"
                style="width: 80px"
                v-if="email.status == 'sent'"
              >
                Sent
              </div>
              <div
                class="btn btn-danger btn-xs"
                v-if="email.status == 'failed'"
                style="width: 80px"
              >
                Failed
              </div>
            </td>
            <td>
              <div class="dropdown btn-group">
                <button
                  class="btn btn-info btn-xs dropdown-toggle"
                  id="dropdownMenuButton"
                  data-toggle="dropdown"
                  :data-target="'email' + email.id"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  Actions
                </button>

                <div
                  :id="'email' + email.id"
                  class="dropdown-menu dropdown-menu-right"
                  aria-labelledby="dropdownMenuButton"
                >
                  <router-link
                    :id="`email${email.id}link`"
                    :to="{
                      name: 'view-email',
                      params: { id: email.id },
                    }"
                    style="cursor: pointer"
                    class="dropdown-item"
                    >view</router-link
                  >
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </datatable>
    </div>

    <div class="pag">
      <div v-if="emails.length < 1" style="text-align: center">
        No data available
      </div>
      <pagination
        :pagination="pagination"
        @prev="getProjects(pagination.prevPageUrl)"
        @next="getProjects(pagination.nextPageUrl)"
        v-if="emails.length > 0"
      >
      </pagination>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "all-emails",
  components: {},
  mounted() {
    this.$emit("done");
    this.getProjects();

    axios
      .get("/api/mails/distinct-data")
      .then((response) => {
        let data = response.data.data;
        let senders = [];
        let receivers = [];
        let subjects = [];
        data.senders.forEach((item) => {
          if (item.from) {
            senders.push(item.from);
          }
        });

        data.receivers.forEach((item) => {
          if (item.to) {
            receivers.push(item.to);
          }
        });

        data.subjects.forEach((item) => {
          subjects.push(item.subject);
        });

        this.senders = senders;
        this.receivers = receivers;
        this.subjects = subjects;
      })
      .catch((error) => {});
  },
  data() {
    let sortOrders = {};

    let columns = [
      { width: "25%", label: "Sender", name: "sender", sortable: false },
      { width: "25%", label: "Receiver", name: "receiver", sortable: false },
      { width: "30%", label: "Subject", name: "subject", sortable: false },
      { width: "10%", label: "Status", name: "status", sortable: false },
      { width: "10%", label: "Actions", name: "actiona", sortable: false },
    ];

    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      subjects: [],
      senders: [],
      receivers: [],
      columns: columns,
      sortKey: "id",
      sortOrders: sortOrders,
      tableData: {
        length: 5,
        sender: "",
        receiver: "",
        subject: "",
        status: "",
      },
      pagination: {
        lastPage: "",
        currentPage: "",
        total: "",
        lastPageUrl: "",
        nextPageUrl: "",
        prevPageUrl: "",
        from: "",
        to: "",
      },
      options: [5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
      show: true,
      emails: [],
    };
  },
  methods: {
    getSender(sender) {
      if (sender) {
        return sender.name ? sender.name : sender.email;
      }
    },
    getProjects(url = "/api/mails/get-all") {
      this.show = true;
      axios
        .post(url, this.tableData)
        .then((response) => {
          this.show = false;
          let data = response.data.data;

          this.emails = data.data;
          this.configPagination(data);
        })
        .catch((errors) => {
          this.show = false;
        });
    },
    configPagination(data) {
      this.pagination.lastPage = data.last_page;
      this.pagination.currentPage = data.current_page;
      this.pagination.total = data.meta.total;
      this.pagination.lastPageUrl = data.last_page_url;
      this.pagination.nextPageUrl = data.links.next;
      this.pagination.prevPageUrl = data.links.prev;
      this.pagination.from = data.meta.from;
      this.pagination.to = data.meta.to;
      this.options.push(data.meta.total);
    },
    sortBy(key) {
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
      this.tableData.column = this.getIndex(this.columns, "name", key);
      this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
      this.getProjects();
    },
    getIndex(array, key, value) {
      return array.findIndex((i) => i[key] == value);
    },
  },
  computed: {
    user() {
      let data = JSON.parse(localStorage.getItem("user"));
      return data.company.user;
    },
  },
};
</script>
<style scoped>
.projects {
  width: 100%;
  margin: 0 auto;
}

.select {
  display: flex;
  justify-content: space-between;
}

.projects .tableFilters {
  margin-bottom: 10px;
}

.projects .tableFilters input {
  width: 100%;
}

.projects .tableFilters .control {
  float: right;
}

.pag {
  margin-top: 20px;
}

h1 {
  text-align: center;
  font-size: 30px;
}
.spinner:before {
  content: "";
  box-sizing: border-box;
  position: absolute;
  top: 50%;
  left: 50%;
  height: 60px;
  width: 60px;
  margin-top: -30px;
  margin-left: -30px;
  border-radius: 50%;
  border: 2px solid transparent;
  border-top-color: #064b96;
  border-bottom-color: #064b96;
  animation: spinner 0.7s ease infinite;
}

@keyframes spinner {
  to {
    transform: rotate(360deg);
  }
}
</style>
