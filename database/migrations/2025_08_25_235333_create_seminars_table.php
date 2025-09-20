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

            $table->boolean('is_active')->default(true)->comment('有効無効フラグ');

            // セミナー基本情報
            $table->string('name')->comment('セミナー名（必須）');
            $table->text('description')->nullable()->comment('セミナーの説明文');
            $table->date('seminar_date')->comment('開催日（必須）');
            $table->time('start_time')->comment('開始時間（必須）');
            $table->time('end_time')->comment('終了時間（必須）');
            $table->string('benefits')->nullable()->comment('参加特典');
            $table->integer('capacity')->nullable()->comment('定員');

            // 主催情報
            $table->string('company_name')->comment('会社名');
            $table->string('company_zip')->nullable()->comment('郵便番号');
            $table->string('company_address')->nullable()->comment('住所');
            $table->string('company_building')->nullable()->comment('建物名');
            $table->string('company_tel')->nullable()->comment('電話番号');
            $table->string('company_url')->nullable()->comment('ホームページ');
            $table->string('company_email')->nullable()->comment('メールアドレス');
            $table->text('company_speaker_info')->nullable()->comment('講師情報');

            // セミナーの種類（onsite, online, webinar）
            $table->string('seminar_type')->comment('セミナーの種類（onsite, online, webinar）');

            // 『seminar_type』が『onsite』の場合に利用
            $table->string('venue_name')->nullable()->comment('会場名');
            $table->string('venue_zip')->nullable()->comment('郵便番号');
            $table->string('venue_address')->nullable()->comment('住所');
            $table->string('venue_building')->nullable()->comment('建物名');
            $table->string('venue_tel')->nullable()->comment('TEL');
            $table->string('venue_map_url')->nullable()->comment('MAPのURL');

            // 『seminar_type』が『online』の場合に利用
            $table->string('online_url')->nullable()->comment('オンラインセミナーのURL(Zoom)');
            $table->string('online_id')->nullable()->comment('ミーティングID(Zoom)');
            $table->string('online_pwd')->nullable()->comment('パスコード(Zoom)');

            // 『seminar_type』が『webinar』の場合に利用
            $table->string('webinar_url')->nullable()->comment('ウェビナー動画のURL');
            $table->dateTime('webinar_start_at')->nullable()->comment('ウェビナー動画の閲覧開始日時');
            $table->dateTime('webinar_end_at')->nullable()->comment('ウェビナー動画の閲覧終了日時');

            // 有料無料設定
            $table->boolean('is_paid')->default(false)->comment('有料セミナーフラグ');
            $table->integer('paid_fee')->nullable()->comment('受講料（有料の場合のみ入力）');

            // 個別相談設定
            $table->boolean('is_consult')->default(true)->comment('個別相談フラグ（trueの場合は個別相談の案内をする）');
            $table->string('timerex_url')->nullable()->comment('TimeRexのURL');

            // 評価設定
            $table->boolean('is_review')->default(true)->comment('レビューフラグ（trueの場合は評価確認を行う）');
            $table->string('google_review_url')->nullable()->comment('Google口コミ投稿のURL');

            $table->string('back_url')->comment('戻るボタンを押した時に転送するURL');
            $table->string('finish_url')->comment('完了後に転送するURL');

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
