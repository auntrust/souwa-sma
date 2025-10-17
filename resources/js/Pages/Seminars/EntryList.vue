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
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                セミナー管理 | エントリー
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
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <h2
                        class="mb-4 text-xl font-bold"
                        style="
                            border-left: 6px solid black;
                            padding-left: 0.5rem;
                        "
                    >
                        {{ seminar?.name }}<br />
                    </h2>

                    <p>{{ seminar?.description }}</p>
                    <br />

                    <SeminarDetails :seminar="seminar!" />

                    <template v-if="seminar?.speaker_info">
                        ▼講師<br />
                        <span v-html="nl2br(seminar.speaker_info)"></span><br />
                    </template>

                    <template v-if="seminar?.benefits">
                        <br />▼特典<br />
                        <span v-html="nl2br(seminar.benefits)"></span>
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
                    class="overflow-hidden bg-white p-2 shadow-sm sm:rounded-lg"
                >
                    <div
                        v-if="props.seminarCustomers?.length == 0"
                        class="m-2 p-4"
                    >
                        エントリーはありません
                    </div>

                    <table
                        v-if="props.seminarCustomers?.length != 0"
                        class="m-3 table-auto border border-gray-400 text-sm"
                    >
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">申込者情報</th>
                                <th class="px-4 py-2">会社情報</th>
                                <th class="px-4 py-2">参加<br />人数</th>
                                <th class="px-4 py-2" style="font-size: 0.8rem">
                                    ご質問・ご要望
                                </th>
                                <th class="px-4 py-2">行動記録</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(
                                    customer, index
                                ) in props.seminarCustomers"
                                :key="customer.id"
                                :class="
                                    Number(index) % 2 === 0
                                        ? 'bg-white'
                                        : 'bg-gray-100'
                                "
                            >
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <span v-if="customer.name"
                                        >{{ customer.name }}
                                    </span>
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
                                <td class="border border-gray-400 px-4 py-2">
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
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ customer.applicant_count }}名
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2"
                                    style="width: 16rem"
                                >
                                    {{ customer.request }}
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
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
                                            class="text-blue-600 underline hover:text-blue-800"
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
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    <DangerButton
                                        @click="
                                            deleteEntry(
                                                customer.id,
                                                customer.name,
                                            )
                                        "
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex items-center justify-center">
                    <Link
                        :href="route('seminars.index')"
                        :class="'rounded-md border bg-gray-500 px-4 py-2 text-xs font-semibold text-white'"
                    >
                        <i class="fa-solid fa-backward"></i> 戻る
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
</style>
