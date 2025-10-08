<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { isPaidLabel, seminarTypeLabel } from '@/utils/format';
import { formatTime } from '@/utils/time';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

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
</script>

<template>
    <Head title="セミナー管理" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                セミナー管理
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
                    <div class="mb-3 ml-3 mt-3 flex">
                        <Link
                            :href="route('seminars.create')"
                            :class="'rounded-md border bg-gray-500 px-4 py-2 font-semibold text-white'"
                        >
                            <i class="fa-solid fa-plus-circle"></i> セミナー登録
                        </Link>

                        <div class="ml-3">
                            <TextInput
                                id="search_str"
                                type="text"
                                class="block w-full"
                                v-model="form.search_str"
                                autocomplete="search_str"
                                @blur="search_go"
                            />
                        </div>
                    </div>

                    <div
                        v-if="props.seminars?.data.length === 0"
                        class="m-2 p-4"
                    >
                        該当するセミナーはありません。
                    </div>

                    <table class="m-3 table-auto border border-gray-400">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">ID</th>
                                <th class="w-auto px-4 py-2">タイトル</th>
                                <th class="px-4 py-2">開催日</th>
                                <th class="px-4 py-2">定員</th>
                                <th class="px-4 py-2">形式</th>
                                <th class="px-4 py-2">区分</th>
                                <th class="px-4 py-2">Key</th>
                                <th class="px-4 py-2"></th>
                                <th class="px-4 py-2"></th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="seminar in seminars?.data"
                                :key="seminar.id"
                                :class="{
                                    'text-gray-400 line-through':
                                        seminar.is_active === 0,
                                }"
                            >
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ seminar.id }}
                                </td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a
                                        :href="`/entry/${seminar.unique_key}`"
                                        target="_blank"
                                        class="underline"
                                    >
                                        {{ seminar.name }}</a
                                    >
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
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
                                    class="border border-gray-400 px-4 py-2 text-center"
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
                                    <span v-else> - </span>
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ seminarTypeLabel(seminar.seminar_type) }}
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ isPaidLabel(seminar.is_paid) }}
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ seminar.unique_key }}
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'seminars.entry_list',
                                                seminar.id,
                                            )
                                        "
                                        :class="'rounded-md border bg-blue-400 px-4 py-2 text-xs text-white'"
                                    >
                                        <i class="fa-solid fa-list"></i>
                                    </Link>
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    <Link
                                        :href="
                                            route('seminars.edit', seminar.id)
                                        "
                                        :class="'rounded-md border bg-yellow-400 px-4 py-2 text-xs text-white'"
                                    >
                                        <i class="fa-solid fa-edit"></i>
                                    </Link>
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    <DangerButton
                                        @click="
                                            deleteSeminar(
                                                seminar.id,
                                                seminar.name,
                                            )
                                        "
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="">
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
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
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
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
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
                                                route('seminars.index', {
                                                    page: link['label'],
                                                    search_str: form.search_str,
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
                                                route('seminars.index', {
                                                    page: link['label'],
                                                    search_str: form.search_str,
                                                })
                                            "
                                            v-else
                                            v-show="link['url'] != null"
                                            type="button"
                                            class="flex min-h-[38px] min-w-[38px] items-center justify-center rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
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
