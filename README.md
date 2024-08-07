# PBL

## 開発メンバー

- [メンバー一覧](./AUTHORS.md)

## 製作物

### 概要

**アプリケーション名：** 健康管理アプリ  
**解決課題：** 視覚的な健康管理による生活習慣の改善

### 使用技術

<img src="https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white"> <img src="https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white"> <img src="https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1"> <img src="https://img.shields.io/badge/bootstrap-%238511FA.svg?style=for-the-badge&logo=bootstrap&logoColor=white"> <img src="https://img.shields.io/badge/Plotly-%233F4F75.svg?style=for-the-badge&logo=plotly&logoColor=white"> <img src="https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white"> <img src="https://img.shields.io/badge/sqlite3-%2307405e.svg?style=for-the-badge&logo=sqlite&logoColor=white"> <img src="https://img.shields.io/badge/shell_script-%23121011.svg?style=for-the-badge&logo=gnu-bash&logoColor=white">

&nbsp;  
**フロントエンド：** HTML, CSS, JavaScript  
**フロントエンドフレームワーク：** Boostrap, Plotly  
**バックエンド** PHP  
**データベース：** SQLite3  
**その他：** POSIX Shell

## 設置方法

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
> - `reset_state.php` 内のパス変更
> - Cron ジョブの設定

### 4. ログイン画面にアクセス

```git
https://your_server/your_path/PBL/pages/login_form.php
```

>[!WARNING]
>`your_server`, `your_path` は自身のサーバ, パスに置き換えること. また, ログイン画面から新規登録画面に遷移し, 新規登録すること.

## 参考文献

### SQLite 関係

- [SQLite3 for PHP](https://rice8y.github.io/sqlite3/)
- [SQLite 公式ドキュメント](https://www.sqlite.org/docs.html)
- [PHP SQLite3 公式マニュアル](https://www.php.net/manual/ja/class.sqlite3.php)

### Boostrap 関係

- [とほほのBootstrap 5入門](https://www.tohoho-web.com/bootstrap5/index.html)
- [Boostrap 公式ドキュメント](https://getbootstrap.jp/docs/5.3/getting-started/introduction/)
  