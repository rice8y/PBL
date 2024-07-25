# PBL

[テストページ](https://sshg.cs.ehime-u.ac.jp/~j496yone/test/PBL/pages/login_form.php)

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
> `init.sh` で行っていることは以下の通り.
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
**FW:** Boostrap, Plotly  
**BE:** PHP  
**DB:** SQLite3

## ファイル

## SQLite 関係

- [SQLite3 for PHP](https://rice8y.github.io/sqlite3/)
- [SQLite 公式ドキュメント](https://www.sqlite.org/docs.html)
- [PHP SQLite3 公式マニュアル](https://www.php.net/manual/ja/class.sqlite3.php)

## Boostrap 関係

- [とほほのBootstrap 5入門](https://www.tohoho-web.com/bootstrap5/index.html)
- [Boostrap 公式ドキュメント](https://getbootstrap.jp/docs/5.3/getting-started/introduction/)
  