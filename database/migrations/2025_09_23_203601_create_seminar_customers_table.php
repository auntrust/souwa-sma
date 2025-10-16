<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seminar_customers', function (Blueprint $table) {
            $table->id();

            // セミナーID（紐付け用）
            $table->unsignedBigInteger('seminar_id')->comment('セミナーID');
            // 顧客ID（紐付け用）
            $table->unsignedBigInteger('customer_id')->comment('顧客ID');

            // 申込日
            $table->date('entry_date')->nullable()->comment('申込日');

            // 顧客情報
            $table->string('name')->comment('氏名');
            $table->string('furigana')->nullable()->comment('ふりがな');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('email')->comment('メールアドレス');
            $table->string('todofuken')->nullable()->comment('都道府県');

            // 会社情報
            $table->string('co_name')->nullable()->comment('会社名');
            $table->string('co_tel')->nullable()->comment('会社電話番号');
            $table->string('co_busho')->nullable()->comment('会社部署');
            $table->string('co_post')->nullable()->comment('役職');

            // 申込人数
            $table->unsignedInteger('applicant_count')->default(1)->comment('申込人数');

            // ご質問・要望
            $table->text('request')->nullable()->comment('ご質問・要望');

            // 顧客情報上書きフラグ
            $table->boolean('is_overwrite_customer_info')->default(false)->comment('顧客情報を上書きするか');

            // 受付メール送信日時
            $table->timestamp('mail_sent_entry_at')->nullable()->comment('受付メール送信日時');
            // 前日メール送信日時
            $table->timestamp('mail_sent_reminder_at')->nullable()->comment('前日メール送信日時');
            // お礼メール送信日時
            $table->timestamp('mail_sent_thank_you_at')->nullable()->comment('お礼メール送信日時');

            // アンケートQ1～Q5回答
            $table->text('survey_q1')->nullable()->comment('アンケートQ1回答');
            $table->text('survey_q2')->nullable()->comment('アンケートQ2回答');
            $table->text('survey_q3')->nullable()->comment('アンケートQ3回答');
            $table->text('survey_q4')->nullable()->comment('アンケートQ4回答');
            $table->text('survey_q5')->nullable()->comment('アンケートQ5回答');
            // アンケート意見
            $table->text('survey_opinion')->nullable()->comment('アンケート意見');
            // アンケート評価（0:低評価, 1:高評価）
            $table->string('survey_rating')->nullable()->comment('アンケート評価（low, high）');
            $table->timestamp('survey_at')->nullable()->comment('アンケート回答日時');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('seminar_id')->references('id')->on('seminars')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar_customers');
    }
};
