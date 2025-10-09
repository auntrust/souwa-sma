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
    is_active: string | boolean;
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
}

const props = defineProps<{ seminar: Partial<SeminarForm> }>();
// console.log(props.seminar);

const form = useForm<SeminarForm>({
    is_active: props.seminar.is_active == '1',
    name: props.seminar.name ?? '',
    description: props.seminar.description ?? '',
    speaker_info: props.seminar.speaker_info ?? '',
    benefits: props.seminar.benefits ?? '',
    detail_url: props.seminar.detail_url ?? '',
    seminar_type: props.seminar.seminar_type ?? '',

    onsite_name: props.seminar.onsite_name ?? '',
    onsite_zip: props.seminar.onsite_zip ?? '',
    onsite_address: props.seminar.onsite_address ?? '',
    onsite_building: props.seminar.onsite_building ?? '',
    onsite_map_url: props.seminar.onsite_map_url ?? '',
    onsite_date: props.seminar.onsite_date ?? '',
    onsite_start_time: props.seminar.onsite_start_time ?? '',
    onsite_end_time: props.seminar.onsite_end_time ?? '',
    onsite_capacity: props.seminar.onsite_capacity ?? '',

    online_url: props.seminar.online_url ?? '',
    online_id: props.seminar.online_id ?? '',
    online_pwd: props.seminar.online_pwd ?? '',
    online_date: props.seminar.online_date ?? '',
    online_start_time: props.seminar.online_start_time ?? '',
    online_end_time: props.seminar.online_end_time ?? '',
    online_capacity: props.seminar.online_capacity ?? '',

    webinar_url: props.seminar.webinar_url ?? '',
    webinar_start_at: props.seminar.webinar_start_at ?? '',
    webinar_end_at: props.seminar.webinar_end_at ?? '',

    organizer_name: props.seminar.organizer_name ?? '',
    organizer_tel: props.seminar.organizer_tel ?? '',
    organizer_email: props.seminar.organizer_email ?? '',

    is_paid: props.seminar.is_paid == '1',
    paid_fee: props.seminar.paid_fee ?? '',

    is_consult: props.seminar.is_consult == '1',
    timerex_url: props.seminar.timerex_url ?? '',

    is_review: props.seminar.is_review == '1',
    google_review_url: props.seminar.google_review_url ?? '',
});

const submit = () => {
    // チェックボックス値を0/1に変換
    form.is_active = form.is_active ? '1' : '0';
    form.is_paid = form.is_paid ? '1' : '0';
    form.is_consult = form.is_consult ? '1' : '0';
    form.is_review = form.is_review ? '1' : '0';

    form.patch(route('seminars.update', props.seminar), {
        onSuccess: () => {
            form.reset(
                'is_active',
                'name',
                'description',
                'speaker_info',
                'benefits',
                'detail_url',
                'seminar_type',

                'onsite_name',
                'onsite_zip',
                'onsite_address',
                'onsite_building',
                'onsite_map_url',
                'onsite_date',
                'onsite_start_time',
                'onsite_end_time',
                'onsite_capacity',

                'online_url',
                'online_id',
                'online_pwd',
                'online_date',
                'online_start_time',
                'online_end_time',
                'online_capacity',

                'webinar_url',
                'webinar_start_at',
                'webinar_end_at',

                'organizer_name',
                'organizer_tel',
                'organizer_email',

                'is_paid',
                'paid_fee',

                'is_consult',
                'timerex_url',

                'is_review',
                'google_review_url',
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
                            <label class="mt-1 inline-flex items-center">
                                <input
                                    id="is_active"
                                    type="checkbox"
                                    class="form-checkbox h-5 w-5 text-blue-600"
                                    v-model="form.is_active"
                                />
                                <span class="ml-2">有効にする</span>
                            </label>

                            <InputError
                                class="mt-2"
                                :message="form.errors.is_active"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="name" value="セミナー名" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
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
                                rows="4"
                            ></textarea>

                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="speaker_info" value="講師情報" />

                            <textarea
                                id="speaker_info"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100"
                                v-model="form.speaker_info"
                                rows="4"
                            ></textarea>

                            <InputError
                                class="mt-2"
                                :message="form.errors.speaker_info"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="benefits" value="特典" />

                            <textarea
                                id="benefits"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100"
                                v-model="form.benefits"
                                rows="4"
                            ></textarea>

                            <InputError
                                class="mt-2"
                                :message="form.errors.benefits"
                            />
                        </div>
                        <div class="mt-4">
                            <InputLabel
                                for="detail_url"
                                value="セミナー詳細ページのURL"
                            />

                            <TextInput
                                id="detail_url"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.detail_url"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.detail_url"
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
                                <InputLabel for="onsite_date" value="開催日" />

                                <TextInput
                                    id="onsite_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.onsite_date"
                                    :required="form.seminar_type === 'onsite'"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.onsite_date"
                                />
                            </div>

                            <div class="mt-4 flex gap-4">
                                <div class="flex-1">
                                    <InputLabel
                                        for="onsite_start_time"
                                        value="開始時間"
                                    />

                                    <TextInput
                                        id="onsite_start_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="form.onsite_start_time"
                                        :required="
                                            form.seminar_type === 'onsite'
                                        "
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.onsite_start_time"
                                    />
                                </div>

                                <div class="flex-1">
                                    <InputLabel
                                        for="onsite_end_time"
                                        value="終了時間"
                                    />

                                    <TextInput
                                        id="onsite_end_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="form.onsite_end_time"
                                        :required="
                                            form.seminar_type === 'onsite'
                                        "
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.onsite_end_time"
                                    />
                                </div>

                                <div class="flex-1">
                                    <InputLabel
                                        for="onsite_capacity"
                                        value="定員"
                                    />

                                    <TextInput
                                        id="onsite_capacity"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.onsite_capacity"
                                        :required="
                                            form.seminar_type === 'onsite'
                                        "
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.onsite_capacity"
                                    />
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="onsite_name" value="会場名" />

                                <TextInput
                                    id="onsite_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.onsite_name"
                                    :required="form.seminar_type === 'onsite'"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.onsite_name"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="onsite_zip" value="郵便番号" />

                                <TextInput
                                    id="onsite_zip"
                                    type="number"
                                    class="mt-1 block w-32"
                                    v-model="form.onsite_zip"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.onsite_zip"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="onsite_address" value="住所" />

                                <TextInput
                                    id="onsite_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.onsite_address"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.onsite_address"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="onsite_building"
                                    value="建物名"
                                />

                                <TextInput
                                    id="onsite_building"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.onsite_building"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.onsite_building"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="onsite_map_url"
                                    value="MAPのURL"
                                />

                                <TextInput
                                    id="onsite_map_url"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.onsite_map_url"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.onsite_map_url"
                                />
                            </div>
                        </div>
                        <div
                            id="boxOnline"
                            v-show="form.seminar_type === 'online'"
                        >
                            <div class="mt-4">
                                <InputLabel for="online_date" value="開催日" />

                                <TextInput
                                    id="online_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.online_date"
                                    :required="form.seminar_type === 'online'"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.online_date"
                                />
                            </div>

                            <div class="mt-4 flex gap-4">
                                <div class="flex-1">
                                    <InputLabel
                                        for="online_start_time"
                                        value="開始時間"
                                    />

                                    <TextInput
                                        id="online_start_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="form.online_start_time"
                                        :required="
                                            form.seminar_type === 'online'
                                        "
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.online_start_time"
                                    />
                                </div>

                                <div class="flex-1">
                                    <InputLabel
                                        for="online_end_time"
                                        value="終了時間"
                                    />

                                    <TextInput
                                        id="online_end_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="form.online_end_time"
                                        :required="
                                            form.seminar_type === 'online'
                                        "
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.online_end_time"
                                    />
                                </div>

                                <div class="flex-1">
                                    <InputLabel
                                        for="online_capacity"
                                        value="定員"
                                    />

                                    <TextInput
                                        id="online_capacity"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.online_capacity"
                                        :required="
                                            form.seminar_type === 'online'
                                        "
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.online_capacity"
                                    />
                                </div>
                            </div>

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
                            <InputLabel
                                for="organizer_name"
                                value="主催会社名"
                            />

                            <TextInput
                                id="organizer_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.organizer_name"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.organizer_name"
                            />
                        </div>

                        <div class="mt-4 flex gap-4">
                            <div class="flex-1">
                                <InputLabel
                                    for="organizer_tel"
                                    value="電話番号"
                                />

                                <TextInput
                                    id="organizer_tel"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.organizer_tel"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.organizer_tel"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel
                                    for="organizer_email"
                                    value="メールアドレス"
                                />

                                <TextInput
                                    id="organizer_email"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.organizer_email"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.organizer_email"
                                />
                            </div>
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
                                :required="form.is_consult == '1'"
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
                                :required="form.is_review == '1'"
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
                                :required="form.is_paid == '1'"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.paid_fee"
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
