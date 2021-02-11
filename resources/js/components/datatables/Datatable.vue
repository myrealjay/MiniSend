<template>
  <div class="table-responsive-sm">
    <table class="table table-striped" style="text-align: left">
      <thead style="background: #0d86ff">
        <tr>
          <th
            v-for="column in columns"
            :key="column.name"
            @click="column.sortable ? emitSomething(column.name) : ''"
            :class="
              column.sortable
                ? sortKey === column.name
                  ? sortOrders[column.name] > 0
                    ? 'sorting_asc'
                    : 'sorting_desc'
                  : 'sorting'
                : ''
            "
            :style="'width:' + column.width + ';' + 'cursor:pointer;'"
            v-html="column.label"
          ></th>
        </tr>
      </thead>
      <slot></slot>
    </table>
  </div>
</template>

<script>
name: "datatable";
export default {
  props: ["columns", "sortKey", "sortOrders"],
  methods: {
    emitSomething(value) {
      if (value !== "actions") {
        this.$emit("sort", value);
      }
    },
  },
};
</script>

<style scoped>
.projects .table {
  width: 100%;
  text-align: left;
  margin-bottom: 0px;
  text-transform: capitalize;
}

.table th {
  text-align: left;
  color: #fff;
}

.projects .table .sorting {
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center right;
}

.projects .table .sorting_asc {
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center right;
}

.projects .table .sorting_desc {
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center right;
}
</style>
