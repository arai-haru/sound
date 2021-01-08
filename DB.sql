-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:8889
-- 生成日時: 2020 年 11 月 13 日 15:15
-- サーバのバージョン： 5.7.26
-- PHP のバージョン: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- データベース: `create_php`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `concerts`
--

CREATE TABLE `concerts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dated` date NOT NULL,
  `place` varchar(255) NOT NULL,
  `todouhuken_id` int(11) NOT NULL,
  `entrance` time NOT NULL,
  `start` time NOT NULL,
  `number_p` int(11) NOT NULL,
  `admission` int(11) NOT NULL,
  `ticket` varchar(255) NOT NULL,
  `program` varchar(500) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `image` mediumblob,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `promote_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `concerts`
--

INSERT INTO `concerts` (`id`, `name`, `dated`, `place`, `todouhuken_id`, `entrance`, `start`, `number_p`, `admission`, `ticket`, `program`, `comment`, `image`, `created_at`, `updated_at`, `promote_id`) VALUES
(1, '定期演奏会', '2020-12-26', '芸術劇場', 14, '13:00:00', '14:00:00', 50, 500, 'eプラスにて販売中', 'うんたらかんたら\r\nルパン三世のマーチ\r\n交響曲第８番', 'どーたらこーたら\r\nいろいろ\r\n楽しい\r\n今回は配信での演奏会をお届け\r\nhttps://kounosu-wind.site/', '', '2020-10-26 01:52:01', '2020-11-06 15:24:00', 1),
(3, '野菜炒め', '2020-11-04', '幕張メッセイベントホール', 12, '15:00:00', '16:00:00', 0, 500, 'e-プラスにて販売中です。\r\n公演名で検索していただければ出てくると思います！', '宇宙の音楽\r\nアルプスの詩\r\nなどなど', '第2部では多くのポップス曲を演奏しますのであなたの知ってる曲もあるかもしれませんよ！', 0x31453031423443322d413642422d343838412d414141392d4442374134393137413437442e706e67, '2020-10-29 00:42:24', '2020-10-29 13:52:05', 2),
(5, '作る', '2020-10-24', '舞浜アンフィシアター', 12, '13:30:00', '14:30:00', 0, 2000, '公式サイトにてチケット販売しています。\r\n又当日券もございますが、当日券は2500円と表示価格より少し高くなりますのでご了承ください。', 'いろいろ', 'いろいろ\r\nお楽しみください', '', '2020-10-30 08:14:38', '1000-01-01 00:00:00', 3),
(6, '冬の定期演奏会', '2021-02-06', '芸術劇場', 6, '18:00:00', '19:00:00', 0, 500, 'イープラスにて販売中', 'あれこれ', 'あれこれ', '', '2020-10-30 09:14:08', '1000-01-01 00:00:00', 2),
(10, 'コンサート', '2020-11-14', '東京国際フォーラム', 13, '13:00:00', '15:00:00', 100, 500, 'イープラスにて販売中', 'jpopや有名アニメのオープニングテーマなど様々な曲を演奏します。', 'ぜひお楽しみください', 0x30463142304545412d374144462d343832302d413731362d4435324435364536304331442e706e67, '2020-11-10 04:57:21', '2020-11-12 17:25:39', 3),
(11, 'a', '2020-11-28', 'a', 1, '17:00:00', '18:00:00', 50, 500, '1', '1', '1', '', '2020-11-12 08:05:07', '1000-01-01 00:00:00', 3);

-- --------------------------------------------------------

--
-- テーブルの構造 `favolite_concerts`
--

CREATE TABLE `favolite_concerts` (
  `id` int(11) NOT NULL,
  `concert_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `favolite_concerts`
--

INSERT INTO `favolite_concerts` (`id`, `concert_id`, `user_id`, `created_at`) VALUES
(1, 10, 2, '2020-11-11 09:44:04'),
(2, 11, 2, '2020-11-12 08:35:26');

-- --------------------------------------------------------

--
-- テーブルの構造 `favolite_promots`
--

CREATE TABLE `favolite_promots` (
  `id` int(11) NOT NULL,
  `promote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `favolite_promots`
--

INSERT INTO `favolite_promots` (`id`, `promote_id`, `user_id`, `created_at`) VALUES
(1, 2, 4, '2020-10-29 05:56:40'),
(2, 2, 2, '2020-11-11 09:43:58');

-- --------------------------------------------------------

--
-- テーブルの構造 `good_concerts`
--

CREATE TABLE `good_concerts` (
  `id` int(11) NOT NULL,
  `concert_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `good_concerts`
--

INSERT INTO `good_concerts` (`id`, `concert_id`, `user_id`, `created_at`) VALUES
(1, 3, 2, '2020-11-05 02:28:08');

-- --------------------------------------------------------

--
-- テーブルの構造 `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `promote_id` int(11) NOT NULL,
  `messages` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `promote_id`, `messages`, `created_at`) VALUES
(1, 2, 'キャベツ', 2, 'yokatta', '2020-11-08 14:40:44'),
(2, 4, 'haru', 3, 'よかった\r\nこれからも応援しています。', '2020-11-09 00:40:48'),
(3, 4, 'haru', 1, 'ああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ', '2020-11-09 01:59:46'),
(4, 6, 'abc', 1, 'この間の演奏会、聞きに行きました！\r\nとても楽しかったです。\r\nまた機会があれば行きたいです。', '2020-11-09 02:08:10'),
(5, 3, '桃', 3, '初めて演奏会に行かせてもらいました。\r\n友人からのお誘いで行ったのですがとてもよかったので次回もまたあれば行きたいです。', '2020-11-09 10:20:52'),
(6, 3, 'ピーチ', 2, '演奏会がある時は毎回行かせてもらっています。特に＊＊パートの演奏が好きです。\r\nこれからも応援しています。', '2020-11-10 04:50:31');

-- --------------------------------------------------------

--
-- テーブルの構造 `promots`
--

CREATE TABLE `promots` (
  `id` int(11) NOT NULL,
  `name` varchar(11) NOT NULL,
  `kana` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(1) NOT NULL COMMENT '0:一般 1:主催者 2:管理者',
  `group_name` varchar(255) NOT NULL,
  `group_class` varchar(11) NOT NULL,
  `group_detail` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `token` varchar(500) DEFAULT NULL,
  `delete_flg` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `promots`
--

INSERT INTO `promots` (`id`, `name`, `kana`, `email`, `password`, `role`, `group_name`, `group_class`, `group_detail`, `created_at`, `updated_at`, `token`, `delete_flg`) VALUES
(1, 'キャベツ', 'きゃべつ', 'ghij@el.jp', '$2y$10$RQCCC3JxweKvhNt3kflfi.bF0wFukO2r15hahon2sH7sBRNiIuoLK', 1, 'キャベツ高校', '高校', '構成人数\r\nパート募集中\r\n活動中\r\nコンクールに出場、東関東大会まで出場経験あり', '2020-10-26 01:50:30', '2020-10-30 11:58:52', NULL, 'FALSE'),
(2, 'にんじん', 'ニンジン', 'ninzin@com.jp', '$2y$10$vL/K3tyQi4ccgU/iKZIhTOf5ujw9nxLwOIa5e0F/ZdP8CfcSJjKiW', 1, 'にんじん中学', '中学校', '野菜\r\n色とりどり', '2020-10-26 01:53:32', '1000-01-01 00:00:00', NULL, 'FALSE'),
(3, 'なす', 'ナス', 'nasu@com.jp', '$2y$10$CxqPOcPoGuO016jbctJpE.VaDSyPMUBzC9Ei73iBI3zk/pW5SU8FK', 1, 'なす団体', '大学', 'なすなす', '2020-10-26 02:35:34', '2020-10-26 11:37:08', NULL, 'FALSE'),
(7, '主催者', 'シュサイシャ', 'syusai@com.jp', '$2y$10$YfP1FNr8qIEwfdtjioDsiuC7tQuilngIDS6KCXH15sIyL5Y2joQyi', 1, '主催団体', '社会人', '主催者の団体です', '2020-11-12 09:28:14', '1000-01-01 00:00:00', NULL, 'FALSE');

-- --------------------------------------------------------

--
-- テーブルの構造 `todouhuken`
--

CREATE TABLE `todouhuken` (
  `id` int(11) NOT NULL,
  `todouhuken_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `todouhuken`
--

INSERT INTO `todouhuken` (`id`, `todouhuken_name`) VALUES
(1, '北海道'),
(2, '青森県'),
(3, '岩手県'),
(4, '宮城県'),
(5, '秋田県'),
(6, '山形県'),
(7, '福島県'),
(8, '茨城県'),
(9, '栃木県'),
(10, '群馬県'),
(11, '埼玉県'),
(12, '千葉県'),
(13, '東京都'),
(14, '神奈川県'),
(15, '新潟県'),
(16, '富山県'),
(17, '石川県'),
(18, '福井県'),
(19, '山梨県'),
(20, '長野県'),
(21, '岐阜県'),
(22, '静岡県'),
(23, '愛知県'),
(24, '三重県'),
(25, '滋賀県'),
(26, '京都府'),
(27, '大阪府'),
(28, '兵庫県'),
(29, '奈良県'),
(30, '和歌山県'),
(31, '鳥取県'),
(32, '島根県'),
(33, '岡山県'),
(34, '広島県'),
(35, '山口県'),
(36, '徳島県'),
(37, '香川県'),
(38, '愛媛県'),
(39, '高知県'),
(40, '福岡県'),
(41, '佐賀県'),
(42, '長崎県'),
(43, '熊本県'),
(44, '大分県'),
(45, '宮崎県'),
(46, '鹿児島県'),
(47, '沖縄県');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(11) NOT NULL,
  `kana` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(1) NOT NULL COMMENT '0:一般 1:主催者 2:管理者',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `token` varchar(500) DEFAULT NULL,
  `delete_flg` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `kana`, `email`, `password`, `role`, `created_at`, `updated_at`, `token`, `delete_flg`) VALUES
(2, '雪', 'ユキ', 'ghij@ek.jp', '$2y$10$zV7C/sEYCXo2D7cQ43ZZ/OF2VV2rqalDMRyjZxOkSaDUTcY5VPj9K', 0, '2020-10-26 01:48:49', '2020-10-29 18:05:31', NULL, 'FALSE'),
(1, 'テスト', 'テスト', 'test.test@com.jp', '$2y$10$UrBSFe3XkQaDVdZz0sf9w.7cgVbsb41OMPgr3FpWr1EBqxtuvWkSK', 0, '2020-11-12 09:26:23', '1000-01-01 00:00:00', NULL, 'FALSE');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `concerts`
--
ALTER TABLE `concerts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `favolite_concerts`
--
ALTER TABLE `favolite_concerts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `favolite_promots`
--
ALTER TABLE `favolite_promots`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `good_concerts`
--
ALTER TABLE `good_concerts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `promots`
--
ALTER TABLE `promots`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `todouhuken`
--
ALTER TABLE `todouhuken`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `concerts`
--
ALTER TABLE `concerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- テーブルのAUTO_INCREMENT `favolite_concerts`
--
ALTER TABLE `favolite_concerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- テーブルのAUTO_INCREMENT `favolite_promots`
--
ALTER TABLE `favolite_promots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- テーブルのAUTO_INCREMENT `good_concerts`
--
ALTER TABLE `good_concerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルのAUTO_INCREMENT `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルのAUTO_INCREMENT `promots`
--
ALTER TABLE `promots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルのAUTO_INCREMENT `todouhuken`
--
ALTER TABLE `todouhuken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
