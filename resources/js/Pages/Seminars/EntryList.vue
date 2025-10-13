<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import SeminarDetails from '@/Components/SeminarDetails.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatTime, nl2br } from '@/utils/format';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    seminar: { type: Object },
    seminarCustomers: { type: Object },
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
    <Head title="セミナー管理 | エントリー 一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                セミナー管理 | エントリー 一覧
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

                    <SeminarDetails :seminar="seminar" />

                    <template v-if="seminar?.speaker_info">
                        <br />
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
                        class="m-3 table-auto border border-gray-400 text-[0.9rem]"
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
                                <th class="px-4 py-2">メール送信ログ</th>
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
                                        <span v-if="customer.name">
                                            （{{ customer.furigana }}）</span
                                        ><br
                                    /></span>
                                    <span v-if="customer.tel"
                                        >{{ customer.tel }}<br
                                    /></span>
                                    <span v-if="customer.email"
                                        >{{ customer.email }}<br
                                    /></span>
                                    <span v-if="customer.todofuken">{{
                                        customer.todofuken
                                    }}</span>
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
                                    <span v-if="customer.mail_sent_at"
                                        >受付完了：{{
                                            formatTime(customer.mail_sent_at)
                                        }}<br
                                    /></span>
                                    <span
                                        v-if="
                                            customer.mail_sent_before_seminar_at
                                        "
                                        >セミナー前日：{{
                                            formatTime(
                                                customer.mail_sent_before_seminar_at,
                                            )
                                        }}<br
                                    /></span>
                                    <span
                                        v-if="
                                            customer.mail_sent_individual_consult_at
                                        "
                                        >個別相談案内：{{
                                            formatTime(
                                                customer.mail_sent_individual_consult_at,
                                            )
                                        }}<br
                                    /></span>
                                    <span v-if="customer.mail_sent_survey_at"
                                        >アンケートメール：{{
                                            formatTime(
                                                customer.mail_sent_survey_at,
                                            )
                                        }}</span
                                    >
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
