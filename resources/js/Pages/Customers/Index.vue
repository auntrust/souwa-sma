<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    customers: { type: Object },
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
const deleteCustomer = (id: number, name: string) => {
    if (
        confirm(
            '削除したデータは復元できません。\n本当に『' +
                name +
                '』を削除しますか？',
        )
    ) {
        form.delete(route('customers.destroy', id));
    }
};

const search_go = () => {
    form.get(route('customers.index'));
};
</script>

<template>
    <Head title="顧客管理" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-2xl font-bold leading-tight tracking-wide text-gray-800 dark:text-gray-200"
            >
                顧客管理
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
                            :href="route('customers.create')"
                            class="rounded-lg bg-gradient-to-r from-blue-500 to-blue-400 px-6 py-3 font-bold text-white shadow-md transition hover:from-blue-600 hover:to-blue-500"
                        >
                            <i class="fa-solid fa-plus-circle mr-2"></i>
                            顧客登録
                        </Link>

                        <div class="ml-6">
                            <TextInput
                                id="search_str"
                                type="text"
                                class="block w-64 rounded-lg border border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                v-model="form.search_str"
                                autocomplete="search_str"
                                placeholder="氏名・会社名で検索"
                                @blur="search_go"
                            />
                        </div>
                    </div>

                    <div
                        v-if="props.customers?.data.length === 0"
                        class="m-2 p-6 text-center text-gray-500"
                    >
                        該当する顧客はありません。
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
                                    氏名
                                </th>
                                <th class="px-4 py-3 font-semibold">Email</th>
                                <th class="px-4 py-3 font-semibold">
                                    都道府県
                                </th>
                                <th class="px-4 py-3 font-semibold">Key</th>
                                <th class="px-4 py-3"></th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="customer in customers?.data"
                                :key="customer.id"
                                :class="[
                                    customer.is_unsubscribe === 1
                                        ? 'bg-gray-50 text-gray-400 line-through'
                                        : 'transition hover:bg-blue-50',
                                ]"
                            >
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ customer.id }}
                                </td>
                                <td class="border border-gray-200 px-4 py-3">
                                    {{ customer.name }}
                                    <span v-if="customer.furigana"
                                        >（{{ customer.furigana }}）</span
                                    >
                                    <div
                                        v-if="customer.co_name"
                                        class="text-xs text-gray-500"
                                    >
                                        {{ customer.co_name }}
                                    </div>
                                </td>
                                <td class="border border-gray-200 px-4 py-3">
                                    {{ customer.email }}
                                </td>
                                <td class="border border-gray-200 px-4 py-3">
                                    {{ customer.todofuken }}
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ customer.unique_key }}
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <Link
                                        :href="
                                            route('customers.edit', customer.id)
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
                                            deleteCustomer(
                                                customer.id,
                                                customer.name,
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
                                    v-for="(link, index) in customers?.links"
                                    :key="index"
                                >
                                    <div v-if="index == 0">
                                        <Link
                                            :href="
                                                route('customers.index', {
                                                    page:
                                                        customers?.current_page -
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
                                            index == customers?.last_page + 1
                                        "
                                    >
                                        <Link
                                            :href="
                                                route('customers.index', {
                                                    page:
                                                        customers?.current_page +
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
                                                route('customers.index', {
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
                                                route('customers.index', {
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
