<template>
  <div class="login-page d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="card shadow-sm login-card">
      <div class="card-body p-4 p-md-5">
        <h5 class="text-center fw-bold mb-4 login-title">Авторизация</h5>
        <form @submit.prevent="onSubmit">
          <div class="mb-3">
            <input v-model="form.login" type="text" class="form-control" placeholder="Логин" />
          </div>
          <div class="mb-3">
            <input v-model="form.password" type="password" class="form-control" placeholder="Пароль" />
          </div>
          <button class="btn w-100 login-btn" type="submit">Войти</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

const form = reactive({ login: '', password: '' });

const onSubmit = () => {
  // Temporary submit handler
  alert(`Логин: ${form.login}\nПароль: ${form.password}`);
};
</script>
