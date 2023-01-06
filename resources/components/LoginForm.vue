<template>
  <form v-if="isAuthenticated != 1" method="post" action="/auth/login" class="login-form">
    <label for="email">Email</label>
    <input type="email" id="email" name="email">
    <label for="password">Password</label>
    <input type="password" id="password" name="password">
    <label for="remember">Remember me</label>
    <input type="checkbox" id="remember" name="remember">
    <br>
    <button>Submit</button>
    <p>{{ isAuthenticated }}</p>
  </form>
  <div v-else>
    You're already signed in.
  </div>
</template>

<script setup>
import {reactive} from 'vue';

defineProps({
  isAuthenticated: String
});

const state = reactive({
  count: 0
});
</script>

<style scoped>
label {
  display: block;
}
</style>
