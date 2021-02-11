
<template>
  <nav class="pull-right" aria-label="Page navigation example">
    <ul class="pagination">
      <li
        v-if="pagination.prevPageUrl"
        @click.prevent="$emit('prev')"
        class="page-item"
        style="background: #00b0e4"
      >
        <a
          class="page-link"
          href="#"
          style="background: #00b0e4; color: #fff !important"
          >Previous</a
        >
      </li>

      <li v-else class="page-item disabled" @click.prevent>
        <a class="page-link" href="#" tabindex="3">Previous</a>
      </li>

      <li class="page-item" @click.prevent>
        <a class="page-link" href="#"
          >{{ pagination.from }} - {{ pagination.to }} of
          {{ pagination.total }}</a
        >
      </li>

      <li
        v-if="pagination.nextPageUrl"
        @click.prevent="$emit('next')"
        class="page-item"
      >
        <a
          class="page-link"
          href="#"
          style="background: #00b0e4; color: #fff !important"
          >Next</a
        >
      </li>

      <li v-else class="page-item disabled" @click.prevent>
        <a class="page-link" href="#" tabindex="3">Next</a>
      </li>
    </ul>
  </nav>
</template>

<script>
name: "pagination";
export default {
  props: ["pagination"],
};
</script>

<style lang="scss" scoped>
.pagination {
  justify-content: flex-end !important;
  color: #000;
  .page-stats {
    align-items: center;
    margin-right: 5px;
  }
  a,
  i {
    color: #000 !important;
    cursor: pointer;
  }
}
</style>