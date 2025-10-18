<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { isPaidLabel, seminarTypeLabel } from '@/utils/format';
import { formatTime } from '@/utils/time';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import { formatDate, formatDateTimeAt } from '@/utils/format';

const props = defineProps({
    seminars: { type: Object },
    search_str: String,
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
    id: '',
    search_str: props.search_str || '',
});
const deleteSeminar = (id: number, name: string) => {
    if (
        confirm(
            '削除したデータは復元できません。\n本当に『' +
                name +
                '』を削除しますか？',
        )
    ) {
        form.delete(route('seminars.destroy', id));
    }
};

const search_go = () => {
    form.get(route('seminars.index'));
};

const showTagModal = ref(false);
const selectedSeminar = ref<any | null>(null);

const openTagModal = (s: any) => {
    selectedSeminar.value = s;
    showTagModal.value = true;
};
const closeTagModal = () => {
    showTagModal.value = false;
};

// 完全URLを生成（SSR想定で window が無い場合は相対URL）
const embedOrigin = typeof window !== 'undefined' ? window.location.origin : '';
const embedUrl = computed(() =>
    selectedSeminar.value
        ? `${embedOrigin}/entry/${selectedSeminar.value.unique_key}`
        : '',
);

const headIncludeHtml = [
    "<link rel='stylesheet' href='" + embedOrigin + "/assets/css/sma.css' />",
    "<script src='" + embedOrigin + "/assets/js/sma.js'><\/script>",
].join('\n');

// 小さめピル型ボタンの設置用タグ（インラインCSSで完結）
const embedButtonHtml = computed(() =>
    selectedSeminar.value
        ? `<a href="${embedUrl.value}" target="_blank" rel="noopener" class="SmaGotoSeminar">お申し込みはこちら</a>`
        : '',
);

const copyButtonText_head = ref('COPY');
const copyButtonText_button = ref('COPY');
const copyButtonText_url = ref('COPY');

const changeCopyButtonText = (type: 'head' | 'button' | 'url') => {
    if (type === 'head') {
        copyButtonText_head.value = 'OK!';
        setTimeout(() => {
            copyButtonText_head.value = 'COPY';
        }, 1500);
    } else if (type === 'button') {
        copyButtonText_button.value = 'OK!';
        setTimeout(() => {
            copyButtonText_button.value = 'COPY';
        }, 1500);
    } else if (type === 'url') {
        copyButtonText_url.value = 'OK!';
        setTimeout(() => {
            copyButtonText_url.value = 'COPY';
        }, 1500);
    }
};

const copyToClipboard = async (
    text: string,
    type: 'head' | 'button' | 'url',
) => {
    try {
        await navigator.clipboard.writeText(text);
        changeCopyButtonText(type); // ボタンのテキストを変更
        setTimeout(() => (successMessage.value = ''), 1500);
    } catch {
        // フォールバック
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        successMessage.value = 'クリップボードにCOPYしました';
        changeCopyButtonText(type); // ボタンのテキストを変更
        setTimeout(() => (successMessage.value = ''), 1500);
    }
};
</script>

<template>
    <Head title="セミナー管理" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-2xl font-bold leading-tight tracking-wide text-gray-800 dark:text-gray-200"
            >
                セミナー管理
            </h2>
        </template>

        <Transition name="fade-pop">
            <div
                v-if="successMessage"
                class="alert alert-success animate-fadeIn fixed left-1/2 top-20 z-50 mx-auto w-fit -translate-x-1/2 rounded-xl border-2 border-green-500 bg-gradient-to-r from-green-100 to-green-200 px-10 py-5 text-center text-lg font-bold text-green-800 shadow-2xl"
            >
                <i class="fa-solid fa-circle-check mr-2"></i>
                {{ successMessage }}
            </div>
        </Transition>

        <div class="py-12">
            <div class="m-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden border border-gray-200 bg-white p-6 shadow-xl sm:rounded-2xl"
                >
                    <div class="mb-6 ml-3 mt-3 flex items-center">
                        <Link
                            :href="route('seminars.create')"
                            class="rounded-lg bg-gradient-to-r from-blue-500 to-blue-400 px-6 py-3 font-bold text-white shadow-md transition hover:from-blue-600 hover:to-blue-500"
                        >
                            <i class="fa-solid fa-plus-circle mr-2"></i>
                            セミナー登録
                        </Link>

                        <div class="ml-6">
                            <TextInput
                                id="search_str"
                                type="text"
                                class="block w-64 rounded-lg border border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                v-model="form.search_str"
                                autocomplete="search_str"
                                placeholder="セミナー名で検索"
                                @blur="search_go"
                            />
                        </div>
                    </div>

                    <div
                        v-if="props.seminars?.data.length === 0"
                        class="m-2 p-6 text-center text-gray-500"
                    >
                        該当するセミナーはありません。
                    </div>

                    <table
                        class="m-3 w-full table-auto overflow-hidden rounded-xl border border-gray-300 text-sm shadow"
                    >
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700"
                            >
                                <th class="px-4 py-3 font-semibold">ID</th>
                                <th class="w-auto px-4 py-3 font-semibold">
                                    タイトル
                                </th>
                                <th class="px-4 py-3 font-semibold">開催日</th>
                                <th class="px-4 py-3 font-semibold">定員</th>
                                <th class="px-4 py-3 font-semibold">形式</th>
                                <th class="px-4 py-3 font-semibold">区分</th>
                                <th class="px-4 py-3 font-semibold">Key</th>
                                <th class="px-4 py-3"></th>
                                <th class="px-4 py-3"></th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="seminar in seminars?.data"
                                :key="seminar.id"
                                :class="[
                                    seminar.is_active === 0
                                        ? 'bg-gray-50 text-gray-400 line-through'
                                        : 'transition hover:bg-blue-50',
                                ]"
                            >
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ seminar.id }}
                                </td>
                                <td class="border border-gray-200 px-4 py-3">
                                    <span class="font-bold">{{
                                        seminar.name
                                    }}</span>

                                    <div class="mt-1 flex gap-2">
                                        <!-- 申し込みフォームボタン -->
                                        <a
                                            :href="`/entry/${seminar.unique_key}`"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 rounded-full border border-gray-300 bg-white px-2.5 py-0.5 text-xs font-medium text-gray-700 shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100"
                                            title="申し込みフォーム"
                                        >
                                            <i
                                                class="fa-solid fa-file-lines text-[10px]"
                                            ></i>
                                            申し込みフォーム
                                        </a>
                                        <!-- 設置用タグボタン -->
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-full border border-gray-300 bg-white px-2.5 py-0.5 text-xs font-medium text-gray-700 shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100"
                                            title="設置用タグを表示"
                                            @click="openTagModal(seminar)"
                                        >
                                            <i
                                                class="fa-solid fa-code text-[10px]"
                                            ></i>
                                            設置用タグ
                                        </button>
                                    </div>
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <div
                                        v-if="seminar.seminar_type == 'onsite'"
                                    >
                                        {{ formatDate(seminar.onsite_date)
                                        }}<br />
                                        {{
                                            formatTime(
                                                seminar.onsite_start_time,
                                            )
                                        }}
                                        -
                                        {{
                                            formatTime(seminar.onsite_end_time)
                                        }}
                                    </div>
                                    <div
                                        v-else-if="
                                            seminar.seminar_type == 'online'
                                        "
                                    >
                                        {{ formatDate(seminar.online_date)
                                        }}<br />
                                        {{
                                            formatTime(
                                                seminar.online_start_time,
                                            )
                                        }}
                                        -
                                        {{
                                            formatTime(seminar.online_end_time)
                                        }}
                                    </div>
                                    <div
                                        v-else-if="
                                            seminar.seminar_type == 'webinar'
                                        "
                                    >
                                        {{
                                            formatDateTimeAt(
                                                seminar.webinar_start_at,
                                            )
                                        }}<br />〜<br />{{
                                            formatDateTimeAt(
                                                seminar.webinar_end_at,
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <span
                                        v-if="seminar.seminar_type == 'onsite'"
                                    >
                                        {{ seminar.online_capacity }}人
                                    </span>
                                    <span
                                        v-if="seminar.seminar_type == 'online'"
                                    >
                                        {{ seminar.online_capacity }}人
                                    </span>
                                    <span
                                        v-if="seminar.seminar_type == 'webinar'"
                                    >
                                        -
                                    </span>
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ seminarTypeLabel(seminar.seminar_type) }}
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ isPaidLabel(seminar.is_paid) }}
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ seminar.unique_key }}
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'seminars.entry_list',
                                                seminar.id,
                                            )
                                        "
                                        class="rounded-lg border bg-blue-400 px-4 py-2 text-xs text-white shadow transition hover:bg-blue-500"
                                    >
                                        <i class="fa-solid fa-list"></i>
                                    </Link>
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <Link
                                        :href="
                                            route('seminars.edit', seminar.id)
                                        "
                                        class="rounded-lg border bg-yellow-400 px-4 py-2 text-xs text-white shadow transition hover:bg-yellow-500"
                                    >
                                        <i class="fa-solid fa-edit"></i>
                                    </Link>
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <DangerButton
                                        @click="
                                            deleteSeminar(
                                                seminar.id,
                                                seminar.name,
                                            )
                                        "
                                        class="rounded-lg px-3 py-2 shadow transition hover:bg-red-600"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        <nav
                            class="mb-2 mt-2 flex items-center gap-x-1"
                            aria-label="Pagination"
                        >
                            <div class="flex items-center gap-x-1">
                                <div
                                    v-for="(link, index) in seminars?.links"
                                    :key="index"
                                >
                                    <div v-if="index == 0">
                                        <Link
                                            :href="
                                                route('seminars.index', {
                                                    page:
                                                        seminars?.current_page -
                                                        1,
                                                    search_str: form.search_str,
                                                })
                                            "
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 shadow transition hover:bg-blue-100 focus:bg-blue-200 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                                        >
                                            <svg
                                                class="mr-1 size-3.5 shrink-0"
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
                                            <span>前へ</span>
                                        </Link>
                                    </div>
                                    <div
                                        v-else-if="
                                            index == seminars?.last_page + 1
                                        "
                                    >
                                        <Link
                                            :href="
                                                route('seminars.index', {
                                                    page:
                                                        seminars?.current_page +
                                                        1,
                                                    search_str: form.search_str,
                                                })
                                            "
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 shadow transition hover:bg-blue-100 focus:bg-blue-200 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                                        >
                                            <span>次へ</span>
                                            <svg
                                                class="ml-1 size-3.5 shrink-0"
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
                                                route('seminars.index', {
                                                    page: link['label'],
                                                    search_str: form.search_str,
                                                })
                                            "
                                            v-if="link['active'] === true"
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg border border-blue-300 bg-blue-200 px-3 py-2 text-sm text-blue-800 shadow focus:bg-blue-300 focus:outline-none disabled:pointer-events-none disabled:opacity-50"
                                            aria-current="page"
                                        >
                                            <span>{{ link['label'] }}</span>
                                        </Link>
                                        <Link
                                            :href="
                                                route('seminars.index', {
                                                    page: link['label'],
                                                    search_str: form.search_str,
                                                })
                                            "
                                            v-else
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 shadow transition hover:bg-blue-100 focus:bg-blue-200 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                                        >
                                            <span>{{ link['label'] }}</span>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <!-- End Pagination -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- ==== 設置用タグモーダル ==== -->
    <Modal :show="showTagModal" max-width="xl" @close="closeTagModal">
        <div class="p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-500">設置用タグ</h3>
                <p class="mt-1 text-sm text-gray-900">
                    <span class="font-bold">{{ selectedSeminar?.name }}</span>
                    （Key: {{ selectedSeminar?.unique_key }}）
                </p>
            </div>

            <div
                class="mb-4 rounded-md border border-yellow-200 bg-yellow-50 p-4"
            >
                <h4 class="text-sm font-semibold text-yellow-800">
                    重要 — &lt;head&gt; に必ず追加してください
                </h4>
                <p class="mt-1 text-sm text-yellow-700">
                    設置ボタンを正しく表示・動作させるため、以下の CSS
                    とスクリプトをサイトの HTML の
                    <code>&lt;head&gt;</code> セクション内に追加してください。
                </p>

                <label class="mt-3 block text-sm font-medium text-gray-700"
                    >head に追加するコード</label
                >
                <div class="mt-1 flex items-center gap-2">
                    <textarea
                        readonly
                        rows="3"
                        class="flex-1 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-800"
                        :value="headIncludeHtml"
                    />
                    <!-- head用COPY -->
                    <button
                        type="button"
                        class="inline-flex items-center gap-1 rounded-md bg-blue-500 px-2.5 py-1 text-xs font-semibold text-white shadow hover:bg-blue-600"
                        @click="copyToClipboard(headIncludeHtml, 'head')"
                    >
                        <i class="fa-solid fa-copy"></i>
                        {{ copyButtonText_head }}
                    </button>
                </div>
            </div>

            <label class="mt-4 block text-sm font-medium text-gray-700"
                >ボタンHTML</label
            >
            <div class="mt-1 flex items-center gap-2">
                <textarea
                    readonly
                    rows="3"
                    class="flex-1 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-800"
                    :value="embedButtonHtml"
                />
                <!-- ボタンHTML用COPY -->
                <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md bg-blue-500 px-2.5 py-1 text-xs font-semibold text-white shadow hover:bg-blue-600"
                    @click="copyToClipboard(embedButtonHtml, 'button')"
                >
                    <i class="fa-solid fa-copy"></i>
                    {{ copyButtonText_button }}
                </button>
            </div>

            <label class="mt-4 block text-sm font-medium text-gray-700"
                >直リンクURL</label
            >
            <div class="mt-1 flex items-center gap-2">
                <input
                    readonly
                    class="flex-1 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-800"
                    :value="embedUrl"
                />
                <!-- URL用COPY -->
                <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md bg-blue-500 px-2.5 py-1 text-xs font-semibold text-white shadow hover:bg-blue-600"
                    @click="copyToClipboard(embedUrl, 'url')"
                >
                    <i class="fa-solid fa-copy"></i>
                    {{ copyButtonText_url }}
                </button>
            </div>

            <div class="mt-6 flex justify-end">
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 shadow-sm hover:bg-gray-50"
                    @click="closeTagModal"
                >
                    閉じる
                </button>
            </div>
        </div>
    </Modal>
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
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.7s;
}
</style>
