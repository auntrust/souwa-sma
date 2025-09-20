<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { seminarTypeLabel } from '@/utils/format';
import { Head, Link, useForm } from '@inertiajs/vue3';

const seminarTypes = [
    { id: 'onsite', name: seminarTypeLabel('onsite') },
    { id: 'online', name: seminarTypeLabel('online') },
    { id: 'webinar', name: seminarTypeLabel('webinar') },
];

interface SeminarForm {
    seminar_type: string;
    name: string;
    description: string;
    seminar_date: string;
    start_time: string;
    end_time: string;
    benefits: string;
    capacity: number | string;
    company_name: string;
    company_zip: string;
    company_address: string;
    company_building: string;
    company_tel: string;
    company_url: string;
    company_email: string;
    company_speaker_info: string;
    is_paid: string | boolean;
    paid_fee: number | string;
    is_consult: string | boolean;
    timerex_url: string;
    is_review: string | boolean;
    google_review_url: string;
    venue_name: string;
    venue_zip: string;
    venue_address: string;
    venue_building: string;
    venue_tel: string;
    venue_map_url: string;
    online_url: string;
    online_id: string;
    online_pwd: string;
    webinar_url: string;
    webinar_start_at: string;
    webinar_end_at: string;
    back_url: string;
    finish_url: string;
}

const props = defineProps<{ seminar: Partial<SeminarForm> }>();
// console.log(props.seminar);

const form = useForm<SeminarForm>({
    seminar_type: props.seminar.seminar_type ?? '',
    name: props.seminar.name ?? '',
    description: props.seminar.description ?? '',
    seminar_date: props.seminar.seminar_date ?? '',
    start_time: props.seminar.start_time ?? '',
    end_time: props.seminar.end_time ?? '',
    benefits: props.seminar.benefits ?? '',
    capacity: props.seminar.capacity ?? '',
    company_name: props.seminar.company_name ?? '',
    company_zip: props.seminar.company_zip ?? '',
    company_address: props.seminar.company_address ?? '',
    company_building: props.seminar.company_building ?? '',
    company_tel: props.seminar.company_tel ?? '',
    company_url: props.seminar.company_url ?? '',
    company_email: props.seminar.company_email ?? '',
    company_speaker_info: props.seminar.company_speaker_info ?? '',
    is_paid: props.seminar.is_paid == '1',
    paid_fee: props.seminar.paid_fee ?? '',
    is_consult: props.seminar.is_consult == '1',
    timerex_url: props.seminar.timerex_url ?? '',
    is_review: props.seminar.is_review == '1',
    google_review_url: props.seminar.google_review_url ?? '',
    venue_name: props.seminar.venue_name ?? '',
    venue_zip: props.seminar.venue_zip ?? '',
    venue_address: props.seminar.venue_address ?? '',
    venue_building: props.seminar.venue_building ?? '',
    venue_tel: props.seminar.venue_tel ?? '',
    venue_map_url: props.seminar.venue_map_url ?? '',
    online_url: props.seminar.online_url ?? '',
    online_id: props.seminar.online_id ?? '',
    online_pwd: props.seminar.online_pwd ?? '',
    webinar_url: props.seminar.webinar_url ?? '',
    webinar_start_at: props.seminar.webinar_start_at ?? '',
    webinar_end_at: props.seminar.webinar_end_at ?? '',
    back_url: props.seminar.back_url ?? '',
    finish_url: props.seminar.finish_url ?? '',
});

const submit = () => {
    // チェックボックス値を0/1に変換
    form.is_paid = form.is_paid ? '1' : '0';
    form.is_consult = form.is_consult ? '1' : '0';
    form.is_review = form.is_review ? '1' : '0';

    form.patch(route('seminars.update', props.seminar), {
        onSuccess: () => {
            form.reset(
                'seminar_type',
                'name',
                'description',
                'seminar_date',
                'start_time',
                'end_time',
                'benefits',
                'capacity',
                'company_name',
                'company_zip',
                'company_address',
                'company_building',
                'company_tel',
                'company_url',
                'company_email',
                'company_speaker_info',
                'is_paid',
                'paid_fee',
                'is_consult',
                'timerex_url',
                'is_review',
                'google_review_url',
                'venue_name',
                'venue_zip',
                'venue_address',
                'venue_building',
                'venue_tel',
                'venue_map_url',
                'online_url',
                'online_id',
                'online_pwd',
                'webinar_url',
                'webinar_start_at',
                'webinar_end_at',
                'back_url',
                'finish_url',
            );
        },
    });
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

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <InputLabel for="name" value="セミナー名" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autocomplete="name"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="description"
                                value="セミナーの説明文"
                            />

                            <textarea
                                id="description"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100"
                                v-model="form.description"
                                required
                                autocomplete="description"
                                rows="4"
                            ></textarea>

                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="seminar_date" value="開催日" />

                            <TextInput
                                id="seminar_date"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="form.seminar_date"
                                required
                                autocomplete="seminar_date"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.seminar_date"
                            />
                        </div>

                        <div class="mt-4 flex gap-4">
                            <div class="flex-1">
                                <InputLabel for="start_time" value="開始時間" />

                                <TextInput
                                    id="start_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    v-model="form.start_time"
                                    required
                                    autocomplete="start_time"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.start_time"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel for="end_time" value="終了時間" />

                                <TextInput
                                    id="end_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    v-model="form.end_time"
                                    required
                                    autocomplete="end_time"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.end_time"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel for="capacity" value="定員" />

                                <TextInput
                                    id="capacity"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.capacity"
                                    required
                                    autocomplete="capacity"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.capacity"
                                />
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="benefits" value="特典" />

                            <textarea
                                id="benefits"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100"
                                v-model="form.benefits"
                                autocomplete="benefits"
                                rows="4"
                            ></textarea>

                            <InputError
                                class="mt-2"
                                :message="form.errors.benefits"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <InputLabel for="company_name" value="開催会社名" />

                            <TextInput
                                id="company_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.company_name"
                                required
                                autocomplete="company_name"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_name"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="company_zip" value="郵便番号" />

                            <TextInput
                                id="company_zip"
                                type="number"
                                class="mt-1 block w-32"
                                v-model="form.company_zip"
                                autocomplete="company_zip"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_zip"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="company_address" value="住所" />

                            <TextInput
                                id="company_address"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.company_address"
                                autocomplete="company_address"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_address"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="company_building" value="建物名" />

                            <TextInput
                                id="company_building"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.company_building"
                                autocomplete="company_building"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_building"
                            />
                        </div>

                        <div class="mt-4 flex gap-4">
                            <div class="flex-1">
                                <InputLabel
                                    for="company_tel"
                                    value="電話番号"
                                />

                                <TextInput
                                    id="company_tel"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.company_tel"
                                    autocomplete="company_tel"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.company_tel"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel
                                    for="company_email"
                                    value="メールアドレス"
                                />

                                <TextInput
                                    id="company_email"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.company_email"
                                    autocomplete="company_email"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.company_email"
                                />
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="company_url"
                                value="ホームページ"
                            />

                            <TextInput
                                id="company_url"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.company_url"
                                autocomplete="company_url"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_url"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="company_speaker_info"
                                value="講師情報"
                            />

                            <textarea
                                id="company_speaker_info"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100"
                                v-model="form.company_speaker_info"
                                autocomplete="company_speaker_info"
                                rows="4"
                            ></textarea>

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_speaker_info"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <InputLabel
                                for="seminar_type"
                                value="セミナーの種類"
                            />

                            <SelectInput
                                :options="seminarTypes"
                                id="seminar_type"
                                class="mt-1 block w-full"
                                v-model="form.seminar_type"
                                required
                                autocomplete="seminar_type"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.seminar_type"
                            />
                        </div>

                        <div
                            id="boxOnsite"
                            v-show="form.seminar_type === 'onsite'"
                        >
                            <div class="mt-4">
                                <InputLabel for="venue_name" value="会場名" />

                                <TextInput
                                    id="venue_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.venue_name"
                                    :required="form.seminar_type === 'onsite'"
                                    autocomplete="venue_name"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.venue_name"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="venue_zip" value="郵便番号" />

                                <TextInput
                                    id="venue_zip"
                                    type="number"
                                    class="mt-1 block w-32"
                                    v-model="form.venue_zip"
                                    autocomplete="venue_zip"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.venue_zip"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="venue_address" value="住所" />

                                <TextInput
                                    id="venue_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.venue_address"
                                    autocomplete="venue_address"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.venue_address"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="venue_building"
                                    value="建物名"
                                />

                                <TextInput
                                    id="venue_building"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.venue_building"
                                    autocomplete="venue_building"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.venue_building"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="venue_tel" value="TEL" />

                                <TextInput
                                    id="venue_tel"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.venue_tel"
                                    autocomplete="venue_tel"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.venue_tel"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="venue_map_url"
                                    value="MAPのURL"
                                />

                                <TextInput
                                    id="venue_map_url"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.venue_map_url"
                                    autocomplete="venue_map_url"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.venue_map_url"
                                />
                            </div>
                        </div>
                        <div
                            id="boxOnline"
                            v-show="form.seminar_type === 'online'"
                        >
                            <div class="mt-4">
                                <InputLabel
                                    for="online_url"
                                    value="オンラインセミナーのURL(Zoom)"
                                />

                                <TextInput
                                    id="online_url"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.online_url"
                                    :required="form.seminar_type === 'online'"
                                    autocomplete="online_url"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.online_url"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="online_id"
                                    value="ミーティングID(Zoom)"
                                />

                                <TextInput
                                    id="online_id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.online_id"
                                    :required="form.seminar_type === 'online'"
                                    autocomplete="online_id"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.online_id"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="online_pwd"
                                    value="パスコード(Zoom)"
                                />

                                <TextInput
                                    id="online_pwd"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.online_pwd"
                                    :required="form.seminar_type === 'online'"
                                    autocomplete="online_pwd"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.online_pwd"
                                />
                            </div>
                        </div>
                        <div
                            id="boxWebinar"
                            v-show="form.seminar_type === 'webinar'"
                        >
                            <div class="mt-4">
                                <InputLabel
                                    for="webinar_url"
                                    value="ウェビナー動画のURL"
                                />

                                <TextInput
                                    id="webinar_url"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.webinar_url"
                                    :required="form.seminar_type === 'webinar'"
                                    autocomplete="webinar_url"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.webinar_url"
                                />
                            </div>

                            <div class="mt-4 flex gap-4">
                                <div class="flex-1">
                                    <InputLabel
                                        for="webinar_start_at"
                                        value="閲覧開始日時"
                                    />

                                    <TextInput
                                        id="webinar_start_at"
                                        type="datetime-local"
                                        class="mt-1 block w-full"
                                        v-model="form.webinar_start_at"
                                        :required="
                                            form.seminar_type === 'webinar'
                                        "
                                        autocomplete="webinar_start_at"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.webinar_start_at"
                                    />
                                </div>

                                <div class="flex-1">
                                    <InputLabel
                                        for="webinar_end_at"
                                        value="閲覧終了日時"
                                    />

                                    <TextInput
                                        id="webinar_end_at"
                                        type="datetime-local"
                                        class="mt-1 block w-full"
                                        v-model="form.webinar_end_at"
                                        :required="
                                            form.seminar_type === 'webinar'
                                        "
                                        autocomplete="webinar_end_at"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.webinar_end_at"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <label class="mt-1 inline-flex items-center">
                                <input
                                    id="is_paid"
                                    type="checkbox"
                                    class="form-checkbox h-5 w-5 text-blue-600"
                                    v-model="form.is_paid"
                                />
                                <span class="ml-2">有料開催</span>
                            </label>

                            <InputError
                                class="mt-2"
                                :message="form.errors.is_paid"
                            />
                        </div>

                        <div class="mt-4" v-show="form.is_paid">
                            <InputLabel for="paid_fee" value="受講料" />

                            <TextInput
                                id="paid_fee"
                                type="number"
                                class="mt-1 block w-32"
                                v-model="form.paid_fee"
                                :required="form.is_paid == true"
                                autocomplete="paid_fee"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.paid_fee"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <label class="mt-1 inline-flex items-center">
                                <input
                                    id="is_consult"
                                    type="checkbox"
                                    class="form-checkbox h-5 w-5 text-blue-600"
                                    v-model="form.is_consult"
                                />
                                <span class="ml-2">個別相談の案内をする</span>
                            </label>

                            <InputError
                                class="mt-2"
                                :message="form.errors.is_consult"
                            />
                        </div>

                        <div class="mt-4" v-show="form.is_consult">
                            <InputLabel
                                for="timerex_url"
                                value="TimeRexのURL"
                            />

                            <TextInput
                                id="timerex_url"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.timerex_url"
                                :required="form.is_consult == true"
                                autocomplete="timerex_url"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.timerex_url"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <label class="mt-1 inline-flex items-center">
                                <input
                                    id="is_review"
                                    type="checkbox"
                                    class="form-checkbox h-5 w-5 text-blue-600"
                                    v-model="form.is_review"
                                />
                                <span class="ml-2"
                                    >評価確認の案内を送信する</span
                                >
                            </label>

                            <InputError
                                class="mt-2"
                                :message="form.errors.is_review"
                            />
                        </div>

                        <div class="mt-4" v-show="form.is_review">
                            <InputLabel
                                for="google_review_url"
                                value="Google口コミ投稿のURL"
                            />

                            <TextInput
                                id="google_review_url"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.google_review_url"
                                :required="form.is_review == true"
                                autocomplete="google_review_url"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.google_review_url"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <InputLabel
                                for="back_url"
                                value="戻るボタンを押した時に転送するURL"
                            />

                            <TextInput
                                id="back_url"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.back_url"
                                required
                                autocomplete="back_url"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.back_url"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="finish_url"
                                value="完了後に転送するURL"
                            />

                            <TextInput
                                id="finish_url"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.finish_url"
                                required
                                autocomplete="finish_url"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.finish_url"
                            />
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            更新
                        </PrimaryButton>
                    </div>

                    <div class="mt-4 flex items-center justify-center">
                        <Link
                            :href="route('seminars.index')"
                            :class="'rounded-md border bg-gray-500 px-4 py-2 text-xs font-semibold text-white'"
                        >
                            <i class="fa-solid fa-backward"></i> 戻る
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
