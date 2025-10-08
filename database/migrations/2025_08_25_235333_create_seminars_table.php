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
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->unique();
            $table->boolean('is_active')->default(true)->comment('有効無効フラグ');

            // セミナー基本情報
            $table->string('name')->comment('セミナー名');
            $table->text('description')->nullable()->comment('セミナーの説明文');
            $table->text('speaker_info')->nullable()->comment('講師情報');
            $table->string('benefits')->nullable()->comment('参加特典');
            $table->string('detail_url')->nullable()->comment('セミナー詳細ページのURL');

            // セミナーの種類（onsite, online, webinar）
            $table->string('seminar_type')->comment('セミナーの種類（onsite, online, webinar）');

            // 『seminar_type』が『onsite』の場合に利用
            $table->date('onsite_date')->nullable()->comment('開催日');
            $table->time('onsite_start_time')->nullable()->comment('開始時間');
            $table->time('onsite_end_time')->nullable()->comment('終了時間');
            $table->integer('onsite_capacity')->nullable()->comment('定員');
            $table->string('onsite_name')->nullable()->comment('会場名');
            $table->string('onsite_zip')->nullable()->comment('郵便番号');
            $table->string('onsite_address')->nullable()->comment('住所');
            $table->string('onsite_building')->nullable()->comment('建物名');
            $table->string('onsite_map_url')->nullable()->comment('MAPのURL');

            // 『seminar_type』が『online』の場合に利用
            $table->date('online_date')->nullable()->comment('開催日');
            $table->time('online_start_time')->nullable()->comment('開始時間');
            $table->time('online_end_time')->nullable()->comment('終了時間');
            $table->integer('online_capacity')->nullable()->comment('定員');
            $table->string('online_url')->nullable()->comment('オンラインセミナーのURL(Zoom)');
            $table->string('online_id')->nullable()->comment('ミーティングID(Zoom)');
            $table->string('online_pwd')->nullable()->comment('パスコード(Zoom)');

            // 『seminar_type』が『webinar』の場合に利用
            $table->dateTime('webinar_start_at')->nullable()->comment('ウェビナー動画の閲覧開始日時');
            $table->dateTime('webinar_end_at')->nullable()->comment('ウェビナー動画の閲覧終了日時');
            $table->string('webinar_url')->nullable()->comment('ウェビナー動画のURL');

            // 主催情報
            $table->string('organizer_name')->nullable()->comment('主催会社名');
            $table->string('organizer_tel')->nullable()->comment('電話番号');
            $table->string('organizer_email')->nullable()->comment('メールアドレス');

            // 有料無料設定
            $table->boolean('is_paid')->default(false)->comment('有料セミナーフラグ');
            $table->integer('paid_fee')->nullable()->comment('受講料（有料の場合のみ入力）');

            // 個別相談設定
            $table->boolean('is_consult')->default(true)->comment('個別相談フラグ（trueの場合は個別相談の案内をする）');
            $table->string('timerex_url')->nullable()->comment('TimeRexのURL');

            // 評価設定
            $table->boolean('is_review')->default(true)->comment('レビューフラグ（trueの場合は評価確認を行う）');
            $table->string('google_review_url')->nullable()->comment('Google口コミ投稿のURL');

            $table->boolean('is_deleted')->default(false)->comment('削除フラグ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
