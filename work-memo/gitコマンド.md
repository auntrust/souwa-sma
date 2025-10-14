===============

# 作業前のコマンド

===============

# 最新の変更を取得

git fetch origin

# mainブランチを最新に更新

git checkout main
git pull origin main

# feature ブランチに戻って統合

git checkout feature/#login-seminar-redirect#
git merge main

===============

# 作業後のコマンド

===============

# 作業後のファイルをステージング＆コミット

git add .
git commit -m "適切なコミットメッセージ"

# リモートにプッシュ

git push origin feature/#login-seminar-redirect#

# プルリク作成

GitHub上で行う

===============

# 作業後のコマンド

===============

# mainブランチに戻る

git checkout main

# 最新のmainを取得

git pull origin main

# 不要になったfeatureブランチを削除

git branch -d feature/#login-seminar-redirect#

# リモートのfeatureブランチも削除（プルリクエストがマージされた後）

git push origin --delete feature/#login-seminar-redirect#
