# 03-簡單 Repository

```sh
# 建立model,controller,migration
# 設計欄位title,content
php artisan make:model Post --controller --migration

# Migrate資料庫
php artisan migrate

# FormRequest的authorize,rules
php artisan make:request PostStoreRequest

# 查詢以下說明
# 1.直接建立資料
- router -> request -> controller -> model
# 2.使用contract,service建立資料
- router -> request -> controller -> service bind contract -> model
# 3.使用facade建立資料
- router -> request -> controller -> service bind facade -> model
# 4.使用DTO建立資料
- router -> request -> controller
- request -> dto -> service bind facade -> model
# 5.使用repository建立資料
- router -> request -> controller
- request -> dto -> service bind facade -> repository -> model
```
