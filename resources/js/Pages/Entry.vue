<script setup lang="ts">
import OrganizerContact from '@/Components/OrganizerContact.vue';
import SeminarDetails from '@/Components/SeminarDetails.vue';
import { nl2br } from '@/utils/format';
import { getTodofukenList } from '@/utils/todofuken';
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

// 都道府県のリストを取得
const todofukenList = getTodofukenList();

const props = defineProps<{
    seminar: {
        id: number;
        name: string;
        description: string;
        speaker_info: string;
        benefits: string;
        detail_url: string;
        seminar_type: string;

        onsite_name: string;
        onsite_zip: string;
        onsite_address: string;
        onsite_building: string;
        onsite_map_url: string;
        onsite_date: string;
        onsite_start_time: string;
        onsite_end_time: string;
        onsite_capacity: number | string;

        online_url: string;
        online_id: string;
        online_pwd: string;
        online_date: string;
        online_start_time: string;
        online_end_time: string;
        online_capacity: number | string;

        webinar_url: string;
        webinar_start_at: string;
        webinar_end_at: string;

        organizer_name: string;
        organizer_tel: string;
        organizer_email: string;

        is_paid: string | boolean;
        paid_fee: string;

        is_consult: string | boolean;
        timerex_url: string;

        is_review: string | boolean;
        google_review_url: string;
    };
    customer: {
        id: number;
        name: string;
        furigana: string;
        tel: string;
        email: string;
        todofuken: string;
        co_name: string;
        co_tel: string;
        co_busho: string;
        co_post: string;
    };
}>();

const form = useForm({
    seminar_id: '',
    customer_id: props.customer?.id ? String(props.customer.id) : '',
    name: props.customer?.name ?? '',
    furigana: props.customer?.furigana ?? '',
    tel: props.customer?.tel ?? '',
    email: props.customer?.email ?? '',
    todofuken: props.customer?.todofuken ?? '',
    co_name: props.customer?.co_name ?? '',
    co_tel: props.customer?.co_tel ?? '',
    co_busho: props.customer?.co_busho ?? '',
    co_post: props.customer?.co_post ?? '',
    applicant_count: '1',
    request: '',
    is_overwrite_customer_info: '',
});

const submit = () => {
    form.is_overwrite_customer_info = form.is_overwrite_customer_info
        ? '1'
        : '0';

    form.seminar_id = String(props.seminar.id);
    // customer.idがnullでない場合のみセット
    if (props.customer != null) {
        form.customer_id = String(props.customer.id);
    }

    form.post(route('seminar_customers.entry_store'), {
        onSuccess: () => {
            form.reset(
                'seminar_id',
                'customer_id',
                'name',
                'furigana',
                'tel',
                'email',
                'todofuken',
                'co_name',
                'co_tel',
                'co_busho',
                'co_post',
                'applicant_count',
                'request',
                'is_overwrite_customer_info',
            );
        },
    });
};
</script>

<template>
    <Head title="セミナー申し込み" />
    <PublicLayout>
        <div
            v-if="seminar.description"
            class="mx-auto mt-10 max-w-xl rounded-xl border border-gray-100 bg-white p-8 shadow-md"
        >
            <h2
                class="mb-6 border-l-8 border-blue-600 pl-3 text-2xl font-extrabold text-blue-900"
            >
                {{ seminar.name }}
            </h2>
            <p
                class="leading-relaxed text-gray-700"
                v-html="nl2br(seminar.description)"
            ></p>
        </div>
        <div
            class="mx-auto mt-10 max-w-xl rounded-xl border border-gray-100 bg-white p-8 shadow-md"
        >
            <div>
                <SeminarDetails :seminar="seminar" :customer="customer" />

                <!-- 現地セミナー -->
                <template v-if="seminar.seminar_type === 'onsite'"> </template>
                <!-- オンラインセミナー -->
                <template v-else-if="seminar.seminar_type === 'online'">
                    <div class="mb-2 mt-6 font-semibold text-blue-700">
                        ▼オンラインセミナーへのアクセス方法
                    </div>
                    <p class="rounded bg-red-50 px-3 py-2 text-sm text-red-600">
                        オンライン受講に必要なURLやアクセス情報は、<br />
                        エントリー完了後にメールでご案内いたします。
                    </p>
                </template>
                <!-- ウェビナー -->
                <template v-else-if="seminar.seminar_type === 'webinar'">
                    <div class="mb-2 mt-6 font-semibold text-blue-700">
                        ▼視聴動画へのアクセス方法
                    </div>
                    <p class="rounded bg-red-50 px-3 py-2 text-sm text-red-600">
                        視聴に必要なURLやアクセス情報は、<br />
                        エントリー完了後にメールでご案内いたします。
                    </p>
                </template>

                <template v-if="seminar.speaker_info">
                    <div class="mb-2 mt-6 font-semibold text-blue-700">
                        ▼講師
                    </div>
                    <span
                        v-html="nl2br(seminar.speaker_info)"
                        class="text-gray-700"
                    ></span>
                </template>

                <template v-if="seminar.benefits">
                    <div class="mb-2 mt-6 font-semibold text-blue-700">
                        ▼特典
                    </div>
                    <span
                        v-html="nl2br(seminar.benefits)"
                        class="text-gray-700"
                    ></span>
                </template>
            </div>
        </div>

        <OrganizerContact
            :organizer-name="seminar.organizer_name"
            :organizer-tel="seminar.organizer_tel"
            :organizer-email="seminar.organizer_email"
        />

        <div
            class="mx-auto mt-10 max-w-xl rounded-xl border border-blue-100 bg-blue-50 p-6 text-center"
        >
            <span class="text-lg font-semibold text-blue-900"
                >下記、エントリー情報をご入力ください</span
            >
        </div>

        <div
            class="mx-auto mt-10 max-w-xl rounded-xl border border-gray-100 bg-white p-8 shadow-md"
        >
            <form @submit.prevent="submit">
                <div class="mb-8">
                    <h2
                        class="mb-6 border-b border-blue-100 pb-2 text-center text-xl font-extrabold text-blue-900"
                    >
                        【お客様情報】
                    </h2>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >氏名<span class="ml-1 text-xs text-red-600"
                                >※必須</span
                            ></label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >ふりがな<span class="ml-1 text-xs text-red-600"
                                >※必須</span
                            ></label
                        >
                        <input
                            v-model="form.furigana"
                            type="text"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >電話番号</label
                        >
                        <input
                            v-model="form.tel"
                            type="text"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >メールアドレス<span
                                class="ml-1 text-xs text-red-600"
                                >※必須</span
                            ></label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >都道府県</label
                        >
                        <select
                            id="todofuken"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-md transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            v-model="form.todofuken"
                        >
                            <option value="">選択してください</option>
                            <option
                                v-for="todofuken in todofukenList"
                                :key="todofuken"
                                :value="todofuken"
                            >
                                {{ todofuken }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-8">
                    <h2
                        class="mb-6 border-b border-blue-100 pb-2 text-center text-xl font-extrabold text-blue-900"
                    >
                        【会社情報】
                    </h2>

                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >会社名</label
                        >
                        <input
                            v-model="form.co_name"
                            type="text"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >電話番号</label
                        >
                        <input
                            v-model="form.co_tel"
                            type="text"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >部署</label
                        >
                        <input
                            v-model="form.co_busho"
                            type="text"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >役職</label
                        >
                        <input
                            v-model="form.co_post"
                            type="text"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                    </div>
                </div>

                <div class="mb-8">
                    <h2
                        class="mb-6 border-b border-blue-100 pb-2 text-center text-xl font-extrabold text-blue-900"
                    >
                        【その他】
                    </h2>

                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >参加人数<span class="ml-1 text-xs text-red-600"
                                >※必須</span
                            ></label
                        >
                        <input
                            v-model="form.applicant_count"
                            type="number"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required
                        />
                    </div>
                    <div class="mb-5">
                        <label class="mb-2 block font-semibold text-gray-800"
                            >ご質問・ご要望など</label
                        >
                        <textarea
                            v-model="form.request"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <p
                    class="mb-6 rounded border border-red-100 bg-red-50 px-4 py-3 text-xs leading-relaxed text-red-700"
                >
                    ご登録いただいたメールアドレスに、<br />
                    関連するセミナーのご案内をお送りする場合があります。<br /><br />
                    お預かりした個人情報は、セミナーのご案内以外の目的には使用せず、<br />
                    第三者に開示・提供することはありません。
                </p>

                <div class="mb-6 text-center" v-if="props.customer">
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            v-model="form.is_overwrite_customer_info"
                            class="form-checkbox accent-blue-600"
                            :true-value="'1'"
                            :false-value="''"
                        />
                        <span class="ml-2 text-gray-700"
                            >入力した内容を登録して次回も利用する</span
                        >
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 py-3 text-lg font-bold text-white shadow transition hover:bg-blue-700 disabled:opacity-40"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    同意してエントリー
                </button>
            </form>
        </div>
    </PublicLayout>
</template>
