<template>
  <b-card class="mt-3" header="New Payment">
    <b-form @submit.prevent="onSubmit">
      <b-form-group id="input-group-1" label="To:" label-for="input-1">
        <b-form-input
          id="input-1"
          size="sm"
          v-model="payment.to"
          type="number"
          required
          placeholder="Destination ID"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
        <b-input-group prepend="$" size="sm">
          <b-form-input
            id="input-2"
            v-model="payment.amount"
            type="number"
            required
            placeholder="Amount"
          ></b-form-input>
        </b-input-group>
      </b-form-group>

      <b-form-group id="input-group-3" label="Details:" label-for="input-3">
        <b-form-input
          id="input-3"
          size="sm"
          v-model="payment.details"
          required
          placeholder="Payment details"
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" size="sm" variant="primary">Submit</b-button>
    </b-form>
  </b-card>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
  data() {
    return {
      payment: {} as object,
    };
  },
  methods: {
    async onSubmit() {
      await this.$axios.post(
        `/accounts/${this.$route.params.id}/transactions`,

        this.payment
      );

      this.payment = {};
      this.$parent.$data.show = false;

      // Update items
      setTimeout(() => {
        /**
         * Emit event to update account data in AccountCard
         * and transactions data in TransactionsTable
         * PS: Vuex state management will be a more ideal approach
         * for such a scenario as the app scales, but I think this will do for now.
         */
        //
        this.$root.$emit("updateAccount");
        this.$root.$emit("updateTransactions");
      }, 200);
    },
  },
});
</script>