<?php
session_start();

function db_connect() {

  try {
    //データベースにアクセスするための変数
    $dsn = 'mysql:host=db;dbname=myapp;charset=utf8';
    $user = 'myappuser';
    $password = 'myapppass';
  
    // クラスに初期設定用のデータを渡し、PDOクラスからPDOインスタンスを作成
    // 初期化の処理を行い、データベースと接続する
    $dbh = new PDO($dsn, $user, $password);
    // 文字化け対策
    $dbh->query('SET NAMES utf8');
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $dbh;
    
    } catch (PODException $e) {
      print "エラー: " . $e->getMessage() . "<br/>";
      die();
    }

}