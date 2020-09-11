<template>
  <div>
    <!-- begin::account card -->
    <AccountCard />
    <!-- end::account card -->

    <!-- begin::payment form -->
    <div v-show="show">
      <PaymentForm />
    </div>
    <!-- end::payment form -->

    <!-- begin::transactions table(payment history) -->
    <TransactionsTable />
    <!-- end::transactions table -->
  </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
  data() {
    return {
      show: false as boolean,
    };
  },
});
</script>
