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
            // 参加人数
            $table->unsignedInteger('entry_count')->default(1)->comment('参加人数');
            // ご質問・要望
            $table->text('request')->nullable()->comment('ご質問・要望');

            // 受付完了メール送信日時
            $table->timestamp('mail_sent_at')->nullable()->comment('受付完了メール送信日時');
            // セミナー前日メール送信日時
            $table->timestamp('mail_sent_before_seminar_at')->nullable()->comment('セミナー前日メール送信日時');
            // 個別相談案内メール送信日時
            $table->timestamp('mail_sent_individual_consult_at')->nullable()->comment('個別相談案内メール送信日時');

            // 個別相談日
            $table->date('individual_consult_date')->nullable()->comment('個別相談日');
            // 個別相談前日メール送信日時
            $table->timestamp('mail_sent_before_individual_consult_at')->nullable()->comment('個別相談前日メール送信日時');

            // アンケートメール送信日時
            $table->timestamp('mail_sent_survey_at')->nullable()->comment('アンケートメール送信日時');
            // アンケートQ1～Q5回答
            $table->string('survey_q1')->nullable()->comment('アンケートQ1回答');
            $table->string('survey_q2')->nullable()->comment('アンケートQ2回答');
            $table->string('survey_q3')->nullable()->comment('アンケートQ3回答');
            $table->string('survey_q4')->nullable()->comment('アンケートQ4回答');
            $table->string('survey_q5')->nullable()->comment('アンケートQ5回答');
            // アンケート意見
            $table->text('survey_opinion')->nullable()->comment('アンケート意見');
            // アンケート評価（0:低評価, 1:高評価）
            $table->unsignedTinyInteger('survey_rating')->nullable()->comment('アンケート評価（0:低評価, 1:高評価）');
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
