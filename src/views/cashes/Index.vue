<template>
  <div class="container">
    <div class="flex">
      <div class="w-full lg:w-8/12 mr-6">
          <div class="w-full mb-8">
            <div class="bg-gray-200 transform -rotate-2 rounded-2xl">
              <div class="bg-gradient-to-br from-blue-500 to-light-blue-400 text-white p-6 transform rotate-2 rounded-2xl">
                <label class="block uppercase text-xs text-blue-100 font-semibold tracking-wider mb-1">balances</label>
                <div class=" text-3xl font-semibold">
                  RP {{ state.balances }}
                </div>
              </div>
            </div>
          </div>
          <div class="flex items-center -mx-2">
              <div class="w-full px-2">
                <div class="bg-gray-200 transform -rotate-3 rounded-2xl">
                  <div class="bg-gradient-to-br from-teal-500 to-cyan-400 text-white p-6 transform rotate-3 rounded-2xl">
                    <label class="block uppercase text-xs text-teal-100 font-semibold tracking-wider mb-1">debit</label>
                    <div class=" text-3xl font-semibold">
                      RP {{ state.debit }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="w-full px-2">
                <div class="bg-gray-200 transform -rotate-3 rounded-2xl">
                  <div class="bg-gradient-to-br from-red-500 to-yellow-400 text-white p-6 transform rotate-3 rounded-2xl">
                    <label class="block uppercase text-xs text-red-100 font-semibold tracking-wider mb-1">credit</label>
                    <div class=" text-3xl font-semibold">
                      RP {{ state.kredit }}
                    </div>
                  </div>
                </div>
              </div>
          </div>

          <div class="my-10">
              <div class="border rounded-lg overflow-hidden">
                  <div class="border-b px-6 py-4 bg-gray-50 flex items-center justify-between">
                    <div>
                        Transaction
                    </div>
                    <form @submit.prevent="getCashes" class="flex items-center">
                        <input type="date" v-model="form.begin" class="bg-white rounded px-3 py-2 border">
                        <input type="date" v-model="form.to" class="bg-white rounded px-3 py-2 border mx-2">
                        <input type="submit" class="px-3 py-2 rounded bg-gradient-to-br from-red-500 to-yellow-500 text-white focus:outline-none" value="Go">
                    </form>
                  </div>
                  <div class="h-112 overflow-y-scroll">
                      <template v-for="transactions in state.transaction" :key="transactions.id">
                        <router-link :to="`/cashes/${transactions.slug}`" class="flex px-6 py-4 justify-between items-center hover:bg-gray-100 border-b">
                          <span class="flex flex-col">
                            <span class="text-gray-500 text-xs">{{ transactions.when }}</span>
                            <span>{{ transactions.name }}</span>
                          </span>
                          <span :class="transactions.isCredit ? 'text-red-500' : 'text-green-500'">{{ transactions.amount }}</span>
                        </router-link>
                      </template>
                  </div>
              </div>
          </div>
      </div>
      <div class="w-full lg:w-4/12">
          <h1 class="font-semibold text-lg text-gray-800 mb-4">
            Add Transaction History
          </h1>
          <form @submit.prevent="add">
              <div class="mb-5">
                  <label for="name" class="text-xs uppercase font-medium block mb-2">
                      Name
                  </label>
                  <input type="text" v-model="form.name" name="name" id="name" class="w-full border rounded-lg focus:outline-none focus:ring focus:border-blue-400 h-10 px-4 transition duration-150">
                 <!-- <div class="text-red-500 mt-2 text-sm" v-if="errors['name']">{{ errors['name'][0] }}</div> -->  
              </div>
              <div class="mb-5">
                  <label for="amount" class="text-xs uppercase font-medium block mb-2">
                      Amount
                  </label>
                  <input type="text" v-model="form.amount" amount="amount" id="amount" class="w-full border rounded-lg focus:outline-none focus:ring focus:border-blue-400 h-10 px-4 transition duration-150">
                <!--  <div class="text-red-500 mt-2 text-sm" v-if="errors['amount']">{{ errors['amount'][0] }}</div>  --> 
              </div>
              <div class="mb-5">
                  <label for="when" class="text-xs uppercase font-medium block mb-2">
                      when
                  </label>
                  <input type="date" v-model="form.when" when="when" id="when" class="w-full border rounded-lg focus:outline-none focus:ring focus:border-blue-400 h-10 px-4 transition duration-150">
                <!--  <div class="text-red-500 mt-2 text-sm" v-if="errors['when']">{{ errors['when'][0] }}</div>  --> 
              </div>
              <div class="mb-5">
                  <label for="description" class="text-xs uppercase font-medium block mb-2">
                      description
                  </label>
                  <textarea v-model="form.description" description="description" id="description" class="w-full border rounded-lg focus:outline-none focus:ring focus:border-blue-400 py-4 px-4 transition duration-150"></textarea>
               <!--   <div class="text-red-500 mt-2 text-sm" v-if="errors['description']">{{ errors['description'][0] }}</div>  -->
              </div>
              <button class="px-4 h-10 rounded-lg focus:ring focus:ring-blue-500 bg-blue-500 hover:bg-blue-600 text-white">
                   Add Transaction
              </button>
          </form>
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue'
import axios from 'axios';
export default {
    setup ()  {
      const state = ref([])

      const form = reactive({
        begin: '',
        to: '',

        name: '',
        amount: '',
        when: '',
        description: '',
      })

      const getCashes = async () => {
        let { data } = await axios.get('api/cash', {
          params: {
            from: form.begin,
            to: form.to
          }
        });
        state.value = data
        form.begin = data.firstOfMounth
        form.to = data.now
      }

      const add = async () => {
        let response = await axios.post('api/cash/create', form)
        state.value.transaction.unshift(response.data.cash)
      }

      onMounted(() => {
        getCashes()
      })

      return { state , form , getCashes , add }
    }
};
</script>

<style>

</style>