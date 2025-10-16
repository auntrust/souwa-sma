<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    emailLogs: { type: Object, required: true },
    search_str: String,
    mail_type: String,
    status: String,
    date_from: String,
    date_to: String,
    stats: { type: Object, required: true },
    successMessage: String,
});

// successMessageをrefでラップ
const successMessage = ref(props.successMessage);

watch(
    () => props.successMessage,
    (val) => {
        successMessage.value = val;
        if (val) {
            setTimeout(() => {
                successMessage.value = '';
            }, 3000);
        }
    },
    { immediate: true },
);

const form = useForm({
    search_str: props.search_str || '',
    mail_type: props.mail_type || '',
    status: props.status || '',
    date_from: props.date_from || '',
    date_to: props.date_to || '',
});

const search_go = () => {
    form.get(route('email-logs.index'));
};

const clearFilters = () => {
    form.search_str = '';
    form.mail_type = '';
    form.status = '';
    form.date_from = '';
    form.date_to = '';
    search_go();
};

// ステータスの日本語ラベル
const getStatusLabel = (status: string) => {
    const statuses: { [key: string]: string } = {
        success: '送信成功',
        failed: '送信失敗',
    };
    return statuses[status] || status;
};

// ステータスのスタイルクラス
const getStatusClass = (status: string) => {
    return status === 'success'
        ? 'bg-green-100 text-green-800'
        : 'bg-red-100 text-red-800';
};

// 日時フォーマット関数
const formatSentAt = (sentAt: string | null) => {
    if (!sentAt) return { date: '-', time: '' };
    const date = new Date(sentAt);
    return {
        date: date.toLocaleDateString('ja-JP'),
        time: date.toLocaleTimeString('ja-JP', {
            hour: '2-digit',
            minute: '2-digit',
        }),
    };
};
</script>

<template>
    <Head title="配信履歴" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                配信履歴
            </h2>
        </template>

        <Transition name="fade-pop">
            <div
                v-if="successMessage"
                class="alert alert-success mx-auto mt-12 w-fit rounded-lg border-2 border-green-500 bg-green-100 px-8 py-4 text-center text-lg font-bold text-green-800 shadow-lg"
                style="z-index: 1000"
            >
                <i class="fa-solid fa-circle-check mr-2"></i>
                {{ successMessage }}
            </div>
        </Transition>

        <div class="py-12">
            <div class="m-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white p-2 shadow-sm sm:rounded-lg"
                >
                    <!-- 検索・フィルター -->
                    <div class="mb-4 rounded-lg bg-gray-50 p-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >検索</label
                                >
                                <TextInput
                                    id="search_str"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.search_str"
                                    placeholder="メール、名前、セミナー名"
                                    @blur="search_go"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >送信状態</label
                                >
                                <select
                                    v-model="form.status"
                                    @change="search_go"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >
                                    <option value="">すべて</option>
                                    <option value="success">送信成功</option>
                                    <option value="failed">送信失敗</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >開始日</label
                                >
                                <input
                                    type="date"
                                    v-model="form.date_from"
                                    @change="search_go"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >終了日</label
                                >
                                <input
                                    type="date"
                                    v-model="form.date_to"
                                    @change="search_go"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                            </div>
                            <div class="flex items-end">
                                <button
                                    @click="clearFilters"
                                    type="button"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    クリア
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- データなしメッセージ -->
                    <div
                        v-if="props.emailLogs?.data.length === 0"
                        class="m-2 p-4 text-center text-gray-500"
                    >
                        該当する配信履歴はありません。
                    </div>

                    <!-- テーブル -->
                    <div v-else class="overflow-x-auto">
                        <table
                            class="min-w-full table-auto border border-gray-400 text-sm"
                        >
                            <thead>
                                <tr class="bg-gray-100">
                                    <th
                                        class="border border-gray-400 px-4 py-2 text-left"
                                    >
                                        ID
                                    </th>
                                    <th
                                        class="border border-gray-400 px-4 py-2 text-left"
                                    >
                                        送信日時
                                    </th>
                                    <th
                                        class="border border-gray-400 px-4 py-2 text-left"
                                    >
                                        種別
                                    </th>
                                    <th
                                        class="border border-gray-400 px-4 py-2 text-left"
                                    >
                                        送信先
                                    </th>
                                    <th
                                        class="border border-gray-400 px-4 py-2 text-left"
                                    >
                                        セミナー名
                                    </th>
                                    <th
                                        class="border border-gray-400 px-4 py-2 text-left"
                                    >
                                        顧客名
                                    </th>
                                    <th
                                        class="border border-gray-400 px-4 py-2 text-center"
                                    >
                                        送信状態
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="log in emailLogs?.data"
                                    :key="log.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td
                                        class="border border-gray-400 px-4 py-2 text-center"
                                    >
                                        {{ log.id }}
                                    </td>
                                    <td
                                        class="border border-gray-400 px-4 py-2"
                                    >
                                        <template
                                            v-if="
                                                typeof formatSentAt(
                                                    log.sent_at,
                                                ) === 'object'
                                            "
                                        >
                                            <div class="text-sm">
                                                {{
                                                    formatSentAt(log.sent_at)
                                                        .date
                                                }}
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                {{
                                                    formatSentAt(log.sent_at)
                                                        .time
                                                }}
                                            </div>
                                        </template>
                                        <template v-else>
                                            {{ formatSentAt(log.sent_at) }}
                                        </template>
                                    </td>
                                    <td
                                        class="border border-gray-400 px-4 py-2"
                                    >
                                        <span
                                            class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800"
                                        >
                                            {{ log.mail_type }}
                                        </span>
                                    </td>
                                    <td
                                        class="border border-gray-400 px-4 py-2"
                                    >
                                        <div class="text-sm font-medium">
                                            {{ log.recipient_name || '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ log.recipient_email }}
                                        </div>
                                    </td>
                                    <td
                                        class="border border-gray-400 px-4 py-2"
                                    >
                                        {{ log.seminar?.name || '-' }}
                                    </td>
                                    <td
                                        class="border border-gray-400 px-4 py-2"
                                    >
                                        {{ log.customer?.name || '-' }}
                                    </td>
                                    <td
                                        class="border border-gray-400 px-4 py-2 text-center"
                                    >
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-medium"
                                            :class="getStatusClass(log.status)"
                                        >
                                            {{ getStatusLabel(log.status) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ページネーション -->
                    <div class="mt-4" v-if="emailLogs?.links">
                        <nav
                            class="flex items-center gap-x-1"
                            aria-label="Pagination"
                        >
                            <div class="flex items-center gap-x-1">
                                <div
                                    v-for="(link, index) in emailLogs?.links"
                                    :key="index"
                                >
                                    <div v-if="index == 0">
                                        <Link
                                            :href="
                                                route('email-logs.index', {
                                                    page:
                                                        emailLogs?.current_page -
                                                        1,
                                                    search_str: form.search_str,
                                                    mail_type: form.mail_type,
                                                    status: form.status,
                                                    date_from: form.date_from,
                                                    date_to: form.date_to,
                                                })
                                            "
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50"
                                        >
                                            <svg
                                                class="size-3.5 shrink-0"
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="m15 18-6-6 6-6"></path>
                                            </svg>
                                            <span>Previous</span>
                                        </Link>
                                    </div>
                                    <div
                                        v-else-if="
                                            index == emailLogs?.last_page + 1
                                        "
                                    >
                                        <Link
                                            :href="
                                                route('email-logs.index', {
                                                    page:
                                                        emailLogs?.current_page +
                                                        1,
                                                    search_str: form.search_str,
                                                    mail_type: form.mail_type,
                                                    status: form.status,
                                                    date_from: form.date_from,
                                                    date_to: form.date_to,
                                                })
                                            "
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50"
                                        >
                                            <span>Next</span>
                                            <svg
                                                class="size-3.5 shrink-0"
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="m9 18 6-6-6-6"></path>
                                            </svg>
                                        </Link>
                                    </div>
                                    <div v-else>
                                        <Link
                                            :href="
                                                route('email-logs.index', {
                                                    page: link['label'],
                                                    search_str: form.search_str,
                                                    mail_type: form.mail_type,
                                                    status: form.status,
                                                    date_from: form.date_from,
                                                    date_to: form.date_to,
                                                })
                                            "
                                            v-if="link['active'] === true"
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg bg-gray-200 px-3 py-2 text-sm text-gray-800 focus:bg-gray-300 focus:outline-none disabled:pointer-events-none disabled:opacity-50"
                                            aria-current="page"
                                        >
                                            <span>{{ link['label'] }}</span>
                                        </Link>
                                        <Link
                                            :href="
                                                route('email-logs.index', {
                                                    page: link['label'],
                                                    search_str: form.search_str,
                                                    mail_type: form.mail_type,
                                                    status: form.status,
                                                    date_from: form.date_from,
                                                    date_to: form.date_to,
                                                })
                                            "
                                            v-else
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50"
                                        >
                                            <span>{{ link['label'] }}</span>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-pop-enter-active,
.fade-pop-leave-active {
    transition:
        opacity 0.5s,
        transform 0.5s;
}
.fade-pop-enter-from,
.fade-pop-leave-to {
    opacity: 0;
    transform: scale(0.9);
}
.fade-pop-enter-to,
.fade-pop-leave-from {
    opacity: 1;
    transform: scale(1);
}
</style>
