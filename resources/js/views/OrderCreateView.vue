<template>
  <div class="order-create-page container py-5 d-flex justify-content-center">
    <div class="card shadow-sm" style="max-width: 720px; width: 100%">
      <div class="card-body p-4 p-md-5">
        <h5 class="fw-bold mb-4">Создание заказа</h5>
        <form @submit.prevent="submitOrder">
          <div class="mb-3">
            <input v-model.trim="customer.fullName" type="text" class="form-control" placeholder="ФИО*" required />
          </div>
          <div class="mb-3">
            <input v-model.trim="customer.phone" type="tel" class="form-control" placeholder="Телефон*" required />
          </div>
          <div class="mb-3">
            <input v-model.trim="customer.email" type="email" class="form-control" placeholder="Почта" />
          </div>
          <div class="mb-3">
            <input v-model.trim="customer.inn" type="text" class="form-control" placeholder="ИНН" />
          </div>
          <div class="mb-3">
            <input v-model.trim="customer.company" type="text" class="form-control" placeholder="Название компании" />
          </div>
          <div class="mb-4">
            <input v-model.trim="customer.address" type="text" class="form-control" placeholder="Адрес" />
          </div>

          <hr class="section my-4" />
          <div class="row small text-muted fw-normal mb-2 products-header">
            <div class="col-12 col-md-7">Товары</div>
            <div class="col-4 col-md-2 text-center">Кол-во</div>
            <div class="col-6 col-md-3">Единицы измерения</div>
          </div>

          <div v-for="(item, index) in items" :key="item.localId" class="row g-2 align-items-center mb-2">
            <div class="col-12 col-md-7">
              <input v-model.trim="item.title" type="text" class="form-control" placeholder="Наименование" @blur="onTitleBlur(index)" />
            </div>
            <div class="col-4 col-md-2">
              <input v-model.number="item.quantity" type="number" min="1" class="form-control qty-input text-center" placeholder="1" />
            </div>
            <div class="col-6 col-md-3">
              <select v-model="item.unit" class="form-select unit-select">
                <option value="pcs">Штуки</option>
                <option value="sets">Комплекты</option>
              </select>
            </div>
          </div>

          <div class="d-none gap-2 mb-4">
            <button type="button" class="btn btn-outline-secondary btn-sm" @click="addItem">Добавить товар</button>
            <button type="button" class="btn btn-outline-danger btn-sm" :disabled="items.length === 1" @click="removeLastItem">Удалить последний</button>
          </div>

          <div v-if="error" class="alert alert-danger py-2" role="alert">{{ error }}</div>
          <div v-if="success" class="alert alert-success py-2" role="alert">Заказ создан</div>

          <button type="submit" class="btn btn-dark w-100" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            Создать заказ
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';

const customer = reactive({
  fullName: '',
  phone: '',
  email: '',
  inn: '',
  company: '',
  address: '',
});

let localIdCounter = 1;
const createItem = () => ({ localId: localIdCounter++, title: '', quantity: 1, unit: 'pcs' });
const items = reactive([createItem()]);

const loading = ref(false);
const error = ref('');
const success = ref(false);

const addItem = () => items.push(createItem());
const removeLastItem = () => { if (items.length > 1) items.splice(items.length - 1, 1); };

// Авто-добавление новой строки товара, когда последняя строка получила наименование
watch(
  () => items[items.length - 1]?.title,
  (value) => {
    if (typeof value === 'string' && value.trim() !== '') {
      items.push(createItem());
    }
  }
);

const onTitleBlur = (index) => {
  const item = items[index];
  if (!item) return;
  const isEmpty = !item.title || String(item.title).trim() === '';
  if (isEmpty && items.length > 1) {
    items.splice(index, 1);
  }
};

const validate = () => {
  if (!customer.fullName?.trim() || !customer.phone?.trim()) return 'Заполните ФИО и телефон';
  return '';
};

const submitOrder = async () => {
  error.value = '';
  success.value = false;
  const validationError = validate();
  if (validationError) { error.value = validationError; return; }

  try {
    loading.value = true;
    const payload = {
      customer: { ...customer },
      items: items.map(({ title, quantity, unit }) => ({ title, quantity, unit })),
    };

    const { data } = await window.axios.post('/api/orders', payload);
    if (data?.id) {
      success.value = true;
    }
  } catch (e) {
    error.value = e?.response?.data?.message || 'Не удалось создать заказ';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.card { border: 0; }
</style>
