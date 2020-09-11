<template>
  <div>
    <div class="container card p-4 mt-4" v-if="loading">Loading transactions...</div>
    <b-card class="mt-3" header="Payment History" v-if="!loading">
      <b-table striped hover :items="transactions"></b-table>
    </b-card>
  </div>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";

/**
 * Define Transaction interface
 */
interface Transaction {
  id: number;
  from: number;
  to: number;
  details: string;
  amount: string;
}

/**
 * Define AccountData interface
 * PS: Vuex will be a more ideal approach to get this done as the app scales
 * but might be an overkill to use right now
 */
interface AccountData {
  id: number;
  currency: string;
}

export default Vue.extend({
  data() {
    return {
      transactions: [] as Array<Transaction>,
      current_account: {} as AccountData,
      loading: true as boolean,
    };
  },
  mounted() {
    /**
     * Call getTransactions
     */
    this.getTransactions();
    /**
     * Listen to updateAccount event emitted from PaymentForm.vue
     * PS: Vuex will be a more ideal approach as app scales
     */
    this.$root.$on("updateTransactions", () => {
      this.getTransactions();
    });
    /**
     * Listen to account event emitted from AccountCard.vue to format transactions amount
     * PS: Vuex will be a more ideal approach as app scales
     */
    this.$root.$on("accountData", (account: AccountData) => {
      this.current_account = account;
    });
  },
  methods: {
    /**
     * Get transactions for account ID passed through route params
     */
    async getTransactions() {
      try {
        const response = await this.$axios.get(
          `/accounts/${this.$route.params.id}/transactions`
        );

        this["transactions"] = response.data;

        var transactions = [];
        for (let i = 0; i < this.transactions.length; i++) {
          this.transactions[i].amount =
            (this.current_account.currency === "usd" ? "$" : "€") +
            this.transactions[i].amount;

          if (this.current_account.id != this.transactions[i].to) {
            this.transactions[i].amount = "-" + this.transactions[i].amount;
          }

          transactions.push(this.transactions[i]);
        }

        this.transactions = transactions;

        // Toggle loading state
        this.loading = false;
      } catch (e) {
        // Toggle loading state
        this.loading = false;
      }
    },
  },
});
</script>