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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->unique();

            $table->string('name')->comment('氏名');
            $table->string('furigana')->nullable()->comment('ふりがな');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('email')->comment('メールアドレス');
            $table->string('todofuken')->nullable()->comment('都道府県');

            $table->string('co_name')->nullable()->comment('会社名');
            $table->string('co_tel')->nullable()->comment('会社電話番号');
            $table->string('co_busho')->nullable()->comment('会社部署');
            $table->string('co_post')->nullable()->comment('役職');

            $table->boolean('is_unsubscribe')->default(false)->comment('配信停止フラグ');
            $table->timestamp('unsubscribe_at')->nullable()->comment('配信停止した日時');
            $table->string('optin_method')->nullable()->comment('オプトインへの同意方法');
            $table->timestamp('optin_at')->nullable()->comment('オプトインに同意した日時');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
