<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    formatDateWithWeekday,
    formatTime,
    getDurationMinutes,
} from '@/utils/format';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    seminar: { type: Object },
    seminarCustomers: { type: Object },
});
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

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <h2 class="mb-4 text-xl font-bold">
                        {{ seminar?.name }}<br />
                        {{ formatDateWithWeekday(seminar?.seminar_date) }}
                    </h2>
                    <div class="">
                        講義時間：{{ formatTime(seminar?.start_time) }}〜{{
                            formatTime(seminar?.end_time)
                        }}（{{
                            getDurationMinutes(
                                seminar?.start_time,
                                seminar?.end_time,
                            )
                        }}分）<br />
                        受講料：{{
                            seminar?.is_paid == '1'
                                ? seminar?.paid_fee.toLocaleString() + '円'
                                : '無料'
                        }}<br />
                        <!-- 開催形式ごとの表示切り替え -->
                        <template v-if="seminar?.seminar_type === 'onsite'">
                            開催形式：現地開催<br />
                            &nbsp;→ 会場名：{{ seminar?.venue_name }}<br />
                            &nbsp;→ 郵便番号：{{ seminar?.venue_zip }}<br />
                            &nbsp;→ 住所：{{ seminar?.venue_address }}
                            {{ seminar?.venue_building }}<br />
                            &nbsp;→ 電話番号：{{ seminar?.venue_tel }}<br />
                            &nbsp;→ 地図URL：<a
                                :href="seminar?.venue_map_url"
                                target="_blank"
                                >{{ seminar?.venue_map_url }}</a
                            ><br />
                        </template>
                        <template
                            v-else-if="seminar?.seminar_type === 'online'"
                        >
                            開催形式：オンラインセミナー<br />
                        </template>
                        <template
                            v-else-if="seminar?.seminar_type === 'webinar'"
                        >
                            開催形式：ウェビナー<br />
                        </template>
                    </div>
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
                                <th class="w-auto px-4 py-2">申込日</th>
                                <th class="w-auto px-4 py-2">参加<br />人数</th>
                                <th class="px-4 py-2">申込者情報</th>
                                <th class="px-4 py-2">会社情報</th>
                                <th class="px-4 py-2">ご質問・ご要望</th>
                                <th class="px-4 py-2">メール送信ログ</th>
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
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ customer.entry_date }}
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    {{ customer.applicant_count }}名
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
                                <td class="border border-gray-400 px-4 py-2">
                                    {{ customer.request }}
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 text-center"
                                >
                                    <span v-if="customer.mail_sent_at"
                                        >受付完了：{{ customer.mail_sent_at
                                        }}<br
                                    /></span>
                                    <span
                                        v-if="
                                            customer.mail_sent_before_seminar_at
                                        "
                                        >セミナー前日：{{
                                            customer.mail_sent_before_seminar_at
                                        }}<br
                                    /></span>
                                    <span
                                        v-if="
                                            customer.mail_sent_individual_consult_at
                                        "
                                        >個別相談案内：{{
                                            customer.mail_sent_individual_consult_at
                                        }}<br
                                    /></span>
                                    <span v-if="customer.mail_sent_survey_at"
                                        >アンケートメール：{{
                                            customer.mail_sent_survey_at
                                        }}</span
                                    >
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
