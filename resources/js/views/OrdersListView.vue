<template>
    <div class="orders-page container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="orders-title">Таблица заказов</h1>
                <form class="orders-filters" @submit.prevent="loadOrders">
                    <div class="filters-item search">
                        <label class="form-label small text-muted">Поиск</label>
                        <input v-model.trim="filters.search" type="text" class="form-control"
                               placeholder="ФИО, компания, телефон, товар"/>
                    </div>
                    <div class="filters-item from">
                        <label class="form-label small text-muted">С даты</label>
                        <div class="input-icon">
                            <input
                                :value="formatDate(filters.dateFrom)"
                                type="text"
                                class="form-control"
                                placeholder="дд.мм.гггг"
                                readonly
                                @click="openFromModal"
                            />
                            <span class="calendar-icon"></span>
                        </div>
                    </div>
                    <div class="filters-item to">
                        <label class="form-label small text-muted">По дату</label>
                        <div class="input-icon">
                            <input
                                :value="formatDate(filters.dateTo)"
                                type="text"
                                class="form-control"
                                placeholder="дд.мм.гггг"
                                readonly
                                @click="openToModal"
                            />
                            <span class="calendar-icon"></span>
                        </div>
                    </div>
                    <div class="filters-item status">
                        <label class="form-label small text-muted">Статус</label>
                        <select v-model="filters.status" class="form-select">
                            <option value="">Все</option>
                            <option value="new">Новые</option>
                            <option value="in_progress">В работе</option>
                            <option value="done">Завершённые</option>
                        </select>
                    </div>
                    <div class="filters-item apply">
                        <label class="form-label invisible">apply</label>
                        <button type="submit" class="btn btn-outline-secondary w-100">Применить</button>
                    </div>
                    <div class="filters-item reset">
                        <label class="form-label invisible">reset</label>
                        <button type="button" class="btn btn-link text-muted w-100 p-0" @click="resetFilters">Сброс
                        </button>
                    </div>
                    <div class="filters-item stats">
                        <label class="form-label invisible">stats</label>
                        <button type="button" class="btn btn-outline-secondary w-100" @click="openStats">Статистика</button>
                    </div>
                </form>
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped table-bordered align-middle text-center orders-table">
                        <thead class="table-light">
                        <tr>
                            <th>Дата</th>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>ИНН</th>
                            <th>Компания</th>
                            <th>Адрес</th>
                            <th>Товар</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="loading">
                            <td colspan="8" class="text-center py-4">
                                <span class="spinner-border spinner-border-sm me-2"></span>Загрузка...
                            </td>
                        </tr>
                        <tr v-else-if="orders.length === 0">
                            <td colspan="8" class="text-center py-4 text-muted">Нет данных</td>
                        </tr>
                        <tr v-for="order in orders" :key="order.id">
                            <td>{{ formatDate(order.date) }}</td>
                            <td>{{ order.customer?.fullName }}</td>
                            <td>{{ order.customer?.phone }}</td>
                            <td>{{ order.customer?.inn }}</td>
                            <td>{{ order.customer?.company }}</td>
                            <td>{{ order.customer?.address }}</td>
                            <td>
                                <div v-for="(item, idx) in order.items" :key="idx" class="product-line">{{ item.title }}
                                    x{{ item.quantity }}
                                </div>
                            </td>
                            <td>
                                {{ statusLabel(order.status) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Временный простой модал-алерт для статистики -->
    <div v-if="statsOpen"
         class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
        <div class="bg-white rounded shadow p-4" style="min-width: 280px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="fw-bold">Статистика</div>
                <button class="btn btn-sm btn-outline-secondary" @click="statsOpen=false">Закрыть</button>
            </div>
            <ul class="list-unstyled mb-0">
                <li>Новые: {{ stats.new }}</li>
                <li>В работе: {{ stats.in_progress }}</li>
                <li>Завершённые: {{ stats.done }}</li>
            </ul>
        </div>
    </div>

    <!-- Модал выбора даты: С даты -->
    <div v-if="fromModalOpen"
         class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
        <div class="bg-white rounded shadow p-4" style="min-width: 320px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="fw-bold">Выберите дату (С даты)</div>
                <button class="btn btn-sm btn-outline-secondary" @click="fromModalOpen=false">Закрыть</button>
            </div>
            <VueDatePicker
                v-model="tempFrom"
                :enable-time-picker="false"
                inline
                @update:modelValue="onFromSelect"
            />
        </div>
    </div>

    <!-- Модал выбора даты: По дату -->
    <div v-if="toModalOpen"
         class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
        <div class="bg-white rounded shadow p-4" style="min-width: 320px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="fw-bold">Выберите дату (По дату)</div>
                <button class="btn btn-sm btn-outline-secondary" @click="toModalOpen=false">Закрыть</button>
            </div>
            <VueDatePicker
                v-model="tempTo"
                :enable-time-picker="false"
                inline
                @update:modelValue="onToSelect"
            />
        </div>
    </div>
</template>

<script setup>
import {onMounted, reactive, ref} from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
const orders = ref([]);
const filters = reactive({
    search: '',
    dateFrom: '',
    dateTo: '',
    status: '',
});
const loading = ref(false);
const statsOpen = ref(false);
const stats = reactive({new: 0, in_progress: 0, done: 0});
// Модальные окна выбора дат
const fromModalOpen = ref(false);
const toModalOpen = ref(false);
const tempFrom = ref('');
const tempTo = ref('');
// Мок-данные для визуальной сверки макета при отсутствии API-данных
const mockOrders = [
    {
        id: 1,
        date: '2025-09-05',
        status: 'new',
        customer: {
            fullName: 'Иванов И.И.',
            phone: '+7 900 123-45-67',
            inn: '123456789101',
            company: 'ООО Пример',
            address: 'Россия, Москва, ул. Тверская, д. 12',
        },
        items: [
            {title: 'Плита ПК 12-2-5', quantity: 1},
            {title: 'Плита ПК 12-2-5', quantity: 2},
            {title: 'Плита ПК 12-2-5', quantity: 3},
            {title: 'Плита ПК 12-2-5', quantity: 4},
        ],
    },
    {
        id: 2,
        date: '2025-09-05',
        status: 'done',
        customer: {
            fullName: 'Петров П.П.',
            phone: '+7 900 123-45-67',
            inn: '',
            company: 'АО СтройИнвест',
            address: 'Россия, Москва, просп. Вернадского, д. 84',
        },
        items: [
            {title: 'Плита ПК 12-2-5', quantity: 1},
            {title: 'Плита ПК 12-2-5', quantity: 2},
            {title: 'Плита ПК 12-2-5', quantity: 3},
        ],
    },
    {
        id: 3,
        date: '2025-09-05',
        status: 'in_progress',
        customer: {
            fullName: 'Петров П.П.',
            phone: '+7 900 123-45-67',
            inn: '',
            company: 'АО СтройИнвест',
            address: 'Россия, Москва, просп. Вернадского, д. 84',
        },
        items: [
            {title: 'Плита ПК 12-2-5', quantity: 1},
            {title: 'Плита ПК 12-2-5', quantity: 2},
            {title: 'Плита ПК 12-2-5', quantity: 3},
        ],
    },
];

const formatDate = (input) => {
    if (!input) return '';
    try {
        const d = input instanceof Date ? input : new Date(input);
        const dd = String(d.getDate()).padStart(2, '0');
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const yyyy = d.getFullYear();
        return `${dd}.${mm}.${yyyy}`;
    } catch {
        return String(input);
    }
};

const normalizeDate = (input) => {
    // Принимает Date или 'дд.мм.гггг' и возвращает 'гггг-мм-дд'
    if (!input) return '';
    if (input instanceof Date) {
        const yyyy = input.getFullYear();
        const mm = String(input.getMonth() + 1).padStart(2, '0');
        const dd = String(input.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }
    const match = String(input).trim().match(/^(\d{2})[\.\/-](\d{2})[\.\/-](\d{4})$/);
    if (match) {
        const [, dd, mm, yyyy] = match;
        return `${yyyy}-${mm}-${dd}`;
    }
    // Попробуем парсить как ISO
    try {
        const d = new Date(input);
        if (!isNaN(d)) {
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }
    } catch {}
    return String(input);
};

const statusLabel = (s) => ({new: 'Новый', in_progress: 'В работе', done: 'Завершён'}[s] || '');
const statusClass = (s) => ({
    new: 'badge text-bg-secondary',
    in_progress: 'badge text-bg-warning',
    done: 'badge text-bg-success',
}[s] || 'badge text-bg-light');

const resetFilters = () => {
    filters.search = '';
    filters.dateFrom = '';
    filters.dateTo = '';
    filters.status = '';
    loadOrders();
};

const buildParams = () => {
    const params = {};
    if (filters.search) params.search = filters.search;
    if (filters.dateFrom) params.date_from = normalizeDate(filters.dateFrom);
    if (filters.dateTo) params.date_to = normalizeDate(filters.dateTo);
    if (filters.status) params.status = filters.status;
    return params;
};

const computeStats = (list) => {
    stats.new = list.filter(o => o.status === 'new').length;
    stats.in_progress = list.filter(o => o.status === 'in_progress').length;
    stats.done = list.filter(o => o.status === 'done').length;
};

const loadOrders = async () => {
    loading.value = true;
    try {
        const {data} = await window.axios.get('/api/orders', {params: buildParams()});
        orders.value = Array.isArray(data?.data) ? data.data : (Array.isArray(data) ? data : []);
        computeStats(orders.value);
    } catch (e) {
        orders.value = mockOrders;
        computeStats(orders.value);
    } finally {
        loading.value = false;
    }
};

const openStats = () => {
    statsOpen.value = true;
};

// Управление модалами дат
const openFromModal = () => {
    tempFrom.value = filters.dateFrom ? (filters.dateFrom instanceof Date ? filters.dateFrom : new Date(filters.dateFrom)) : new Date();
    fromModalOpen.value = true;
};
const openToModal = () => {
    tempTo.value = filters.dateTo ? (filters.dateTo instanceof Date ? filters.dateTo : new Date(filters.dateTo)) : new Date();
    toModalOpen.value = true;
};
const onFromSelect = (value) => {
    filters.dateFrom = value;
    fromModalOpen.value = false;
};
const onToSelect = (value) => {
    filters.dateTo = value;
    toModalOpen.value = false;
};

onMounted(loadOrders);
</script>
