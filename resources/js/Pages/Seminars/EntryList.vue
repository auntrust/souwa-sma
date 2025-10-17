<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import SeminarDetails from '@/Components/SeminarDetails.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatShortDateTime, nl2br } from '@/utils/format';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

type Seminar = {
    seminar_type: string;
    detail_url: string;
    onsite_name?: string;
    onsite_zip?: string;
    onsite_address?: string;
    onsite_building?: string;
    // ... add all other required properties here ...
    is_paid: boolean;
    paid_fee: string;
    [key: string]: any; // fallback for any extra properties
};

const props = defineProps<{
    seminar: Seminar;
    seminarCustomers: any;
    successMessage?: string;
}>();

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
});

const deleteEntry = (id: number, name: string) => {
    if (
        confirm(
            '削除したデータは復元できません。\n本当に『' +
                name +
                '』を削除しますか？',
        )
    ) {
        form.delete(route('seminar_customers.destroy', id));
    }
};
</script>

<template>
    <Head title="セミナー管理 | エントリー" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-2xl font-bold leading-tight tracking-wide text-gray-800 dark:text-gray-200"
            >
                セミナー管理 | エントリー
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
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="mb-4 overflow-hidden border border-gray-200 bg-white p-8 shadow-xl sm:rounded-2xl"
                >
                    <h2
                        class="mb-4 border-l-8 border-blue-500 pl-3 text-xl font-bold"
                    >
                        {{ seminar?.name }}<br />
                    </h2>

                    <p class="mb-2 text-gray-700">{{ seminar?.description }}</p>
                    <br />

                    <SeminarDetails :seminar="seminar!" />

                    <template v-if="seminar?.speaker_info">
                        <div class="mt-4 text-gray-800">
                            <span class="font-semibold text-blue-700"
                                >▼講師</span
                            ><br />
                            <span v-html="nl2br(seminar.speaker_info)"></span
                            ><br />
                        </div>
                    </template>

                    <template v-if="seminar?.benefits">
                        <div class="mt-4 text-gray-800">
                            <span class="font-semibold text-blue-700"
                                >▼特典</span
                            ><br />
                            <span v-html="nl2br(seminar.benefits)"></span>
                        </div>
                    </template>

                    <OrganizerContact
                        :organizer-name="seminar?.organizer_name"
                        :organizer-tel="seminar?.organizer_tel"
                        :organizer-email="seminar?.organizer_email"
                    />
                </div>
            </div>

            <div class="m-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden border border-gray-200 bg-white p-6 shadow-xl sm:rounded-2xl"
                >
                    <div
                        v-if="props.seminarCustomers?.length == 0"
                        class="m-2 p-6 text-center text-gray-500"
                    >
                        エントリーはありません
                    </div>

                    <table
                        v-if="props.seminarCustomers?.length != 0"
                        class="m-3 w-full table-auto overflow-hidden rounded-xl border border-gray-300 text-sm shadow"
                    >
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700"
                            >
                                <th class="px-4 py-3 font-semibold">No</th>
                                <th class="px-4 py-3 font-semibold">
                                    申込者情報
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    会社情報
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    参加<br />人数
                                </th>
                                <th
                                    class="px-4 py-3 font-semibold"
                                    style="font-size: 0.8rem"
                                >
                                    ご質問・ご要望
                                </th>
                                <th class="px-4 py-3 font-semibold">
                                    行動記録
                                </th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(
                                    customer, index
                                ) in props.seminarCustomers"
                                :key="customer.id"
                                :class="[
                                    Number(index) % 2 === 0
                                        ? 'bg-white'
                                        : 'bg-gray-50',
                                    'transition hover:bg-blue-50',
                                ]"
                            >
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td class="border border-gray-200 px-4 py-3">
                                    <span v-if="customer.name">{{
                                        customer.name
                                    }}</span>
                                    <span v-if="customer.tel"
                                        ><br />{{ customer.tel }}</span
                                    >
                                    <span v-if="customer.email"
                                        ><br />{{ customer.email }}</span
                                    >
                                    <span v-if="customer.todofuken"
                                        ><br />{{ customer.todofuken }}</span
                                    >
                                </td>
                                <td class="border border-gray-200 px-4 py-3">
                                    <span v-if="customer.co_name"
                                        >{{ customer.co_name }}<br
                                    /></span>
                                    <span v-if="customer.co_tel"
                                        >{{ customer.co_tel }}<br
                                    /></span>
                                    <span v-if="customer.co_post"
                                        >役職：{{ customer.co_post }}<br
                                    /></span>
                                    <span v-if="customer.co_busho"
                                        >部署：{{ customer.co_busho }}<br
                                    /></span>
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    {{ customer.applicant_count }}名
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3"
                                    style="width: 16rem"
                                >
                                    {{ customer.request }}
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <span v-if="customer.mail_sent_entry_at"
                                        >受付メール送信：{{
                                            formatShortDateTime(
                                                customer.mail_sent_entry_at,
                                            )
                                        }}<br
                                    /></span>
                                    <span v-if="customer.mail_sent_reminder_at"
                                        >前日メール送信：{{
                                            formatShortDateTime(
                                                customer.mail_sent_reminder_at,
                                            )
                                        }}<br
                                    /></span>
                                    <span v-if="customer.webinar_view_at"
                                        >ウェビナー視聴：{{
                                            formatShortDateTime(
                                                customer.webinar_view_at,
                                            )
                                        }}<br
                                    /></span>
                                    <span v-if="customer.mail_sent_thank_you_at"
                                        >お礼メール送信：{{
                                            formatShortDateTime(
                                                customer.mail_sent_thank_you_at,
                                            )
                                        }}<br
                                    /></span>
                                    <span v-if="customer.survey_at"
                                        ><Link
                                            class="font-semibold text-blue-600 underline hover:text-blue-800"
                                            :href="
                                                route(
                                                    'seminar_customers.show_feedback',
                                                    {
                                                        sid: seminar?.id,
                                                        cid: customer.customer
                                                            .id,
                                                    },
                                                )
                                            "
                                            >アンケート入力：{{
                                                formatShortDateTime(
                                                    customer.survey_at,
                                                )
                                            }}</Link
                                        ><br
                                    /></span>
                                </td>
                                <td
                                    class="border border-gray-200 px-4 py-3 text-center"
                                >
                                    <DangerButton
                                        @click="
                                            deleteEntry(
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
                </div>
                <div class="mt-6 flex items-center justify-center">
                    <Link
                        :href="route('seminars.index')"
                        class="rounded-lg bg-gradient-to-r from-gray-500 to-gray-400 px-6 py-3 text-xs font-bold text-white shadow transition hover:from-gray-600 hover:to-gray-500"
                    >
                        <i class="fa-solid fa-backward mr-2"></i> 戻る
                    </Link>
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
