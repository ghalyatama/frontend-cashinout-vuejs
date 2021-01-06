<template>
  <div class="border-b lg:py-3">
    <div class="flex flex-col lg:flex-row justify-between">
        <div class="flex justify-between items-center border-b lg:border-b-0 py-4 lg:py-0 px-6 lg:pr-0">
            <router-link exact-active-class="bg-transparent" class="font-semibold uppercase" to="/"> 
                Cashinout
            </router-link>
        
            <button @click="isOn = !isOn" class="block lg:hidden focus:outline-none">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path :class="!isOn ? 'block' :  'hidden'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    <path :class="isOn ? 'block' :  'hidden'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
           
            </button>
        </div>
        <div :class=" isOn ? 'block' : 'hidden' " class="py-4 lg:py-0 lg:flex  flex-col lg:flex-row justify-between lg:items-center w-full px-6">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <router-link :class="className" to="/about">About</router-link>   
                <router-link :class="className" to="/cashes">Cash</router-link>   
            </div>
            <div class="flex items-center" v-if="authenticated">
                <router-link :class="className" to="/login">{{ user.name }}</router-link>
                <button @click="logout" class="focus:outline-none" :class="className">Logout</button>
            </div>
            <div class="flex flex-col lg:flex-row lg:items-center" v-else>
                <router-link :class="className" to="/login">Login</router-link>
                <router-link :class="className" to="/register">Register</router-link>
            </div>
        </div>
    </div>
  </div>    
</template>

<script>
import { computed, ref } from 'vue'
import store from'@/store'
export default {
    setup () {
        const className = "px-4 py-2"
        const isOn = ref(false)

        const authenticated = computed(() => store.getters['auth/authenticated'])
        const user = computed(() => store.getters['auth/user'])

        const logout = async () => {
            store.dispatch("auth/logout")
        }

        return { className, isOn, authenticated, user, logout }
    }
}
</script>

<style>

</style>