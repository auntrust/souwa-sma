<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getTodofukenList } from '@/utils/todofuken';
import { Head, Link, useForm } from '@inertiajs/vue3';

// 都道府県のリストを取得
const todofukenList = getTodofukenList();

const form = useForm({
    name: '',
    furigana: '',
    tel: '',
    email: '',
    todofuken: '',
    co_name: '',
    co_tel: '',
    co_busho: '',
    co_post: '',
    is_unsubscribe: '',
    unsubscribe_at: '',
    optin_at: '',
    optin_method: '',
});

const submit = () => {
    // チェックボックス値を0/1に変換
    form.is_unsubscribe = form.is_unsubscribe ? '1' : '0';

    form.post(route('customers.store'), {
        onSuccess: () => {
            form.reset(
                'name',
                'furigana',
                'tel',
                'email',
                'todofuken',
                'co_name',
                'co_tel',
                'co_busho',
                'co_post',
                'is_unsubscribe',
                'optin_at',
                'optin_method',
            );
        },
    });
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

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div
                        class="mb-6 overflow-hidden border border-gray-200 bg-white p-8 shadow-xl sm:rounded-2xl"
                    >
                        <div>
                            <label class="mt-1 inline-flex items-center gap-2">
                                <input
                                    id="is_unsubscribe"
                                    type="checkbox"
                                    class="form-checkbox h-5 w-5 text-blue-600 accent-blue-500 focus:ring-blue-400"
                                    v-model="form.is_unsubscribe"
                                    :true-value="1"
                                    :false-value="0"
                                />
                                <span class="ml-2 font-semibold text-gray-700"
                                    >新規セミナーの案内メールを配信しない</span
                                >
                            </label>
                            <InputError
                                class="mt-2"
                                :message="form.errors.is_unsubscribe"
                            />
                        </div>
                        <div class="mt-6">
                            <InputLabel for="optin_at" value="オプトイン同意" />
                            <TextInput
                                id="optin_at"
                                type="datetime-local"
                                class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                v-model="form.optin_at"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.optin_at"
                            />
                        </div>
                        <div class="mt-6">
                            <InputLabel
                                for="optin_method"
                                value="オプトインの方法"
                            />
                            <TextInput
                                id="optin_method"
                                type="text"
                                class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                v-model="form.optin_method"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.optin_method"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-6 overflow-hidden border border-gray-200 bg-white p-8 shadow-xl sm:rounded-2xl"
                    >
                        <div class="mt-6 flex gap-6">
                            <div class="flex-1">
                                <InputLabel for="name" value="氏名" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    v-model="form.name"
                                    required
                                    autocomplete="name"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>
                            <div class="flex-1">
                                <InputLabel for="furigana" value="ふりがな" />
                                <TextInput
                                    id="furigana"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    v-model="form.furigana"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.furigana"
                                />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="tel" value="電話番号" />
                            <TextInput
                                id="tel"
                                type="text"
                                class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                v-model="form.tel"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.tel"
                            />
                        </div>
                        <div class="mt-6">
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="text"
                                class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                v-model="form.email"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>
                        <div class="mt-6">
                            <InputLabel for="todofuken" value="都道府県" />
                            <select
                                id="todofuken"
                                class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
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
                            <InputError
                                class="mt-2"
                                :message="form.errors.todofuken"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-6 overflow-hidden border border-gray-200 bg-white p-8 shadow-xl sm:rounded-2xl"
                    >
                        <div class="mt-6 flex gap-6">
                            <div class="flex-1">
                                <InputLabel for="co_name" value="会社名" />
                                <TextInput
                                    id="co_name"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    v-model="form.co_name"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_name"
                                />
                            </div>
                            <div class="flex-1">
                                <InputLabel for="co_tel" value="電話番号" />
                                <TextInput
                                    id="co_tel"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    v-model="form.co_tel"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_tel"
                                />
                            </div>
                        </div>
                        <div class="mt-6 flex gap-6">
                            <div class="flex-1">
                                <InputLabel for="co_busho" value="部署" />
                                <TextInput
                                    id="co_busho"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    v-model="form.co_busho"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_busho"
                                />
                            </div>
                            <div class="flex-1">
                                <InputLabel for="co_post" value="役職" />
                                <TextInput
                                    id="co_post"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2 shadow transition focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    v-model="form.co_post"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_post"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end">
                        <PrimaryButton
                            class="ms-4 rounded-lg bg-gradient-to-r from-blue-500 to-blue-400 px-8 py-3 font-bold text-white shadow transition hover:from-blue-600 hover:to-blue-500"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            登録
                        </PrimaryButton>
                    </div>

                    <div class="mt-8 flex items-center justify-center">
                        <Link
                            :href="route('customers.index')"
                            class="rounded-lg bg-gradient-to-r from-gray-500 to-gray-400 px-6 py-3 text-xs font-bold text-white shadow transition hover:from-gray-600 hover:to-gray-500"
                        >
                            <i class="fa-solid fa-backward mr-2"></i> 戻る
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
