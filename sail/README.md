# laravel sail 的補充擋案

- ./sail/supervisord.conf
  - 加入horizon設定
  - 修改docker-compose.yml,當sail up時啓用

- ./sail/mysql/create-cms-database.sh
  - 建立cms資料庫腳本
  - 修改docker-compose.yml,當sail up時啓用
