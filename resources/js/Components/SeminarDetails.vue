<script setup lang="ts">
import {
    formatDateTimeAtWithWeekday,
    formatDateWithWeekday,
    formatPostalCode,
    formatTime,
    getDurationMinutes,
} from '@/utils/format';

const props = defineProps<{
    seminar: {
        unique_key?: string;
        seminar_type: string;
        detail_url: string;
        onsite_name?: string;
        onsite_zip?: string;
        onsite_address?: string;
        onsite_building?: string;
        onsite_map_url?: string;
        onsite_date?: string;
        onsite_start_time?: string;
        onsite_end_time?: string;
        onsite_capacity?: number | string;
        online_url?: string;
        online_id?: string;
        online_pwd?: string;
        online_date?: string;
        online_start_time?: string;
        online_end_time?: string;
        online_capacity?: number | string;
        webinar_url?: string;
        webinar_start_at?: string;
        webinar_end_at?: string;
        is_paid: string | boolean;
        paid_fee: string;
    };
    customer?: {
        unique_key?: string;
        [key: string]: any;
    };
}>();

/**
 * URL に sma_c（customer.unique_key）を追加して返す
 * - customer が渡されていない場合はそのまま返す
 * - 既に sma_c が含まれている場合は追加しない
 */
function appendSmaParams(url?: string | null) {
    if (!url) return '';

    // customer がない、または unique_key がない場合はそのまま返す
    if (!props.customer?.unique_key) {
        return url;
    }

    const sep = url.includes('?') ? '&' : '?';
    return (
        url +
        sep +
        `sma_c=${encodeURIComponent(String(props.customer.unique_key))}`
    );
}
</script>

<template>
    <!-- 現地セミナー -->
    <template v-if="seminar.seminar_type === 'onsite'">
        ▼日程<br />
        講義日：{{ formatDateWithWeekday(seminar.onsite_date ?? '') }}<br />
        講義時間：{{ formatTime(seminar.onsite_start_time ?? '') }}〜{{
            formatTime(seminar.onsite_end_time ?? '')
        }}（{{
            getDurationMinutes(
                seminar.onsite_start_time ?? '',
                seminar.onsite_end_time ?? '',
            )
        }}分）<br />
        受講料：{{
            seminar.is_paid == '1'
                ? seminar.paid_fee.toLocaleString() + '円'
                : '無料'
        }}<br />
        定員：{{ seminar.onsite_capacity }}人<br /><br />

        ▼セミナー詳細<br />
        <a
            :href="appendSmaParams(seminar.detail_url)"
            target="_blank"
            class="inline-block rounded bg-blue-500 px-3 py-1 text-xs font-bold text-white transition hover:bg-blue-600"
        >
            セミナーの詳細はこちら</a
        ><br /><br />

        ▼会場<br />
        {{ seminar.onsite_name }}<br />
        {{ formatPostalCode(seminar.onsite_zip ?? '') }}<br />
        {{ seminar.onsite_address }}<br />
        {{ seminar.onsite_building }}<br />
        <a
            :href="seminar.onsite_map_url"
            target="_blank"
            class="inline-block rounded bg-blue-500 px-3 py-1 text-xs font-bold text-white transition hover:bg-blue-600"
        >
            地図を開く </a
        ><br />
    </template>
    <!-- オンラインセミナー -->
    <template v-else-if="seminar.seminar_type === 'online'">
        ▼日程<br />
        講義日：{{ formatDateWithWeekday(seminar.online_date ?? '') }}<br />
        講義時間：{{ formatTime(seminar.online_start_time ?? '') }}〜{{
            formatTime(seminar.online_end_time ?? '')
        }}（{{
            getDurationMinutes(
                seminar.online_start_time ?? '',
                seminar.online_end_time ?? '',
            )
        }}分）<br />
        定員：{{ seminar.online_capacity }}人<br />
        受講料：{{
            seminar.is_paid == '1'
                ? seminar.paid_fee.toLocaleString() + '円'
                : '無料'
        }}<br /><br />
        <template v-if="seminar.detail_url">
            ▼オンラインセミナー詳細<br />
            <a
                :href="appendSmaParams(seminar.detail_url)"
                target="_blank"
                class="inline-block rounded bg-blue-500 px-3 py-1 text-xs font-bold text-white transition hover:bg-blue-600"
            >
                オンラインセミナーの詳細はこちら</a
            ><br /><br />
        </template>
    </template>
    <!-- ウェビナー -->
    <template v-else-if="seminar.seminar_type === 'webinar'">
        ▼視聴期間<br />
        {{ formatDateTimeAtWithWeekday(seminar.webinar_start_at ?? '') }}
        〜<br />
        {{ formatDateTimeAtWithWeekday(seminar.webinar_end_at ?? '') }}<br />
        <p class="text-sm text-red-600">
            視聴期間を過ぎると視聴できなくなりますので、<br />期間中にご視聴ください。
        </p>
        <br />
        ▼ウェビナー詳細<br />
        <a
            :href="appendSmaParams(seminar.detail_url)"
            target="_blank"
            class="inline-block rounded bg-blue-500 px-3 py-1 text-xs font-bold text-white transition hover:bg-blue-600"
        >
            ウェビナーの詳細はこちら</a
        ><br /><br />
        ▼視聴料<br />
        {{
            seminar.is_paid == '1'
                ? seminar.paid_fee.toLocaleString() + '円'
                : '無料'
        }}<br /><br />
    </template>
</template>
