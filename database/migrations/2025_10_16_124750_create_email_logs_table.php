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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();

            // メール種別
            $table->string('mail_type')->comment('メール種別');

            // 送信先情報
            $table->string('recipient_email')->comment('送信先メールアドレス');
            $table->string('recipient_name')->nullable()->comment('送信先名前');

            // 関連ID
            $table->unsignedBigInteger('seminar_id')->nullable()->comment('セミナーID');
            $table->unsignedBigInteger('customer_id')->nullable()->comment('顧客ID');
            $table->unsignedBigInteger('seminar_customer_id')->nullable()->comment('セミナー申込ID');

            // 送信状態
            $table
                ->enum('status', [
                    'pending', // 送信待ち
                    'success', // 送信成功
                    'failed', // 送信失敗
                ])
                ->default('pending')
                ->comment('送信状態');

            // 送信日時・エラー情報
            $table->timestamp('sent_at')->nullable()->comment('送信日時');
            $table->text('error_message')->nullable()->comment('エラーメッセージ');

            $table->timestamps();

            // インデックス
            $table->index(['mail_type', 'status']);
            $table->index(['seminar_id', 'mail_type']);
            $table->index(['customer_id', 'mail_type']);
            $table->index(['recipient_email', 'sent_at']);
            $table->index('sent_at');

            // 外部キー制約
            $table->foreign('seminar_id')->references('id')->on('seminars')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('seminar_customer_id')->references('id')->on('seminar_customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
