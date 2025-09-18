<template>
  <div class="login-page d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="card shadow-sm login-card">
      <div class="card-body p-4 p-md-5">
        <h5 class="text-center fw-bold mb-4 login-title">Авторизация</h5>
        <form @submit.prevent="onSubmit">
          <div class="mb-3">
            <input v-model="form.email" type="email" class="form-control" placeholder="Email" />
          </div>
          <div class="mb-3">
            <input v-model="form.password" type="password" class="form-control" placeholder="Пароль" />
          </div>
          <div v-if="error" class="alert alert-danger py-2" role="alert">{{ error }}</div>
          <button class="btn w-100 login-btn" type="submit" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Войти
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = reactive({ email: '', password: '' });
const loading = ref(false);
const error = ref('');

const onSubmit = async () => {
  error.value = '';
  loading.value = true;
  try {
    const { data } = await window.axios.post('/api/login', {
      email: form.email,
      password: form.password,
    });

    const token = data?.token;
    if (!token) {
      throw new Error('Токен не получен');
    }

    localStorage.setItem('auth_token', token);
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    try {
      const roles = Array.isArray(data?.user?.roles) ? data.user.roles : [];
      localStorage.setItem('auth_roles', JSON.stringify(roles));
      if (roles.includes('manager')) {
        await router.push({ name: 'orders.create' });
      } else if (roles.includes('head')) {
        await router.push({ name: 'orders.index' });
      } else {
        await router.push({ name: 'home' });
      }
    } catch {
      await router.push({ name: 'home' });
    }
  } catch (e) {
    if (e?.response?.status === 422) {
      error.value = e.response.data?.message || 'Неверные учетные данные';
    } else {
      error.value = 'Ошибка входа. Попробуйте позже.';
    }
  } finally {
    loading.value = false;
  }
};
</script>
