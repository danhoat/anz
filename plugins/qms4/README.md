# QMS4

## インストール

まず、リポジトリを適当なところにクローンします。
```bash
$ git clone git@bitbucket.org:team_qh/tailor.git
```

### composer インストール

オートローダの読み込みしかしてませんがインストールしないとエラーになるので実行しておきます。
```bash
cd /##PATH TO CLONE##/plugins/qms4
$ composer install
```

Wordpressがインストールされているディレクトリにコピーまたはシンボリックリンクを張ります。<br />
開発環境やQMS4自体をバージョン管理する場合はシンボリックリンクが推奨です。

コピーする場合
```bash
$ cp /##PATH TO CLONE##/plugins/qms4 /##PATH TO WP-INSTALL##/wp-content/plugins/qms4
```

シンボリックリンクを張る場合
```bash
$ ln -s /##PATH TO CLONE##/plugins/qms4 /##PATH TO WP-INSTALL##/wp-content/plugins/qms4
```

### Wordpressでインストール

Wordpressの 管理画面 > プラグイン からQMS4を有効化します。

## JavaScript開発環境構築

前提条件としてNode.jsが必要になります。

前述のシンボリックリンクを張った状態を前提に説明します。<br />
clone先のディレクトリに移動して、plugins/qms4に移動してnpmインストールを行います。

nodeモジュールをインストール
```bash
$ cd /##PATH TO CLONE##/plugins/qms4
$ npm install
```

これでトランスパイルのコマンドが使えるようになります。<br />
対象は `blocks/src` 以下になります。書き出し先は `blocks/build` 以下です。

単体でトランスパイル
```bash
$ npm run build
```

変更を監視するコマンド
```bash
$ npm run start
```

