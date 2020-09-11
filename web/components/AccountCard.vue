<template>
  <div>
    <div class="container card p-4 mt-4" v-if="loading">Loading account...</div>
    <b-card :header="'Welcome, ' + account.name" class="mt-3" v-if="!loading">
      <b-card-text>
        <div>
          Account:
          <code>{{ account.name }}</code>
        </div>
        <div>
          Account Number:
          <code>{{ account.id }}</code>
        </div>
        <div>
          Balance:
          <code>
            {{ account.currency === "usd" ? "$" : "€"
            }}{{ account.balance }}
          </code>
        </div>
      </b-card-text>
      <b-button size="sm" variant="success" @click="toggleShow">New payment</b-button>

      <b-button class="float-right" variant="danger" size="sm" nuxt-link to="/">Logout</b-button>
    </b-card>
  </div>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";

/**
 * Define Account interface
 */
interface Account {
  id: number;
  name: string;
  balance: number;
  currency: string;
}

export default Vue.extend({
  data() {
    return {
      account: {} as Account,
      show: this.$parent.$data.show,
      loading: true as boolean,
    };
  },
  mounted() {
    /**
     * Call getAccount
     */
    this.getAccount();

    /**
     * Listen to updateAccount event emitted from PaymentForm.vue
     * PS: Vuex will be a more ideal approach as app scales
     */
    this.$root.$on("updateAccount", () => {
      this.getAccount();
    });
  },
  methods: {
    /**
     * Get account data for account ID passed through route params
     */
    async getAccount() {
      try {
        const response = await this.$axios.get(
          `/accounts/${this.$route.params.id}`
        );

        if (Object.keys(response.data).length === 0) {
          this.$router.push("/");
        } else {
          this.account = response.data;

          /**
           * Emit account ID to TransactionsTable.vue to use of amount formatting
           */
          this.$root.$emit("accountData", {
            id: this.account.id,
            currency: this.account.currency,
          });

          // Toggle loading state
          this.loading = false;
        }
      } catch (e) {
        // Toggle loading state
        this.loading = false;
      }
    },
    /**
     * Toggle the show data property in parent component
     */
    toggleShow() {
      this.$parent.$data.show = !this.$parent.$data.show;
    },
  },
});
</script>