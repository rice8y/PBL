# PBL

[テストページ](https://sshg.cs.ehime-u.ac.jp/~j496yone/pblone/test/login_form.php)

## Usage

### 1. git clone

公開ディレクトリ配下で git clone する.

```git
git clone https://github.com/rice8y/PBL.git
cd PBL
```

### 2. 初期設定

```git
chmod +x init.sh
./init.sh
```

>[!NOTE]
> `init.sh` で行っていることは以下の通りです.
> - データベースの作成
> - パーミッションの変更
> - Cron ジョブの設定

### 4. ログイン画面にアクセス

```git
https://your_server/your_path/PBL/pages/login_form.php
```

>[!WARNING]
>`your_server`, `your_path` は自身のサーバ, パスに置き換えること. また, ログイン画面から新規登録画面に遷移し, 新規登録すること.

## 製作物

- 健康管理アプリ

## 環境

**FE:** HTML, CSS, JavaScript  
**FW:** Boostrap  
**BE:** PHP  
**DB:** SQLite3

## ファイル

### テストファイル

- [login_form.php](pages/login_form.php): ログイン画面
  - [login.php](pages/login.php): ログイン処理
- [register_form.php](pages/register_form.php): 新規登録画面
  - [register.php](pages/register.php): 新規登録処理
- [home.php](pages/home.php): ホーム
  - [set_data.php](pages/set_data.php): プロットデータ追加処理
- [set_goals_form.php](pages/set_goals_form.php): 目標設定画面
  - [set_goals.php](pages/set_goals.php): 目標値追加処理
- [profile.php](pages/profile.php): 基本情報画面
  - [set_profile.php](pages/set_profile.php): 基本情報追加処理
- [checklist.php](pages/checklist.php): チェックリスト画面
  - [add_list.php](pages/add_list.php): タスク追加処理
  - [delete_list.php](pages/delete_list.php): タスク削除処理
  - [change_state.php](pages/change_state.php): タスク状況監視処理
  - [reset_state.php](pages/reset_state.php): タスク状況リセット処理
- [mail_form.php](pages/mail_form.php): お問い合わせ画面
  - [thanks.php](pages/thanks.php): Thanks画面
- [logout_form.php](pages/logout_form.php): ログアウト画面
  - [logout.php](pages/logout.php): ログアウト処理

## SQLite 関係

- [SQLite3 for PHP](https://rice8y.github.io/sqlite3/)
- [SQLite 公式ドキュメント](https://www.sqlite.org/docs.html)
- [PHP SQLite3 公式マニュアル](https://www.php.net/manual/ja/class.sqlite3.php)

## Boostrap 関係

- [とほほのBootstrap 5入門](https://www.tohoho-web.com/bootstrap5/index.html)
- [Boostrap 公式ドキュメント](https://getbootstrap.jp/docs/5.3/getting-started/introduction/)
  