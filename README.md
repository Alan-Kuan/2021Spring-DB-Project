# 2021Spring-DB-Project
A project of Introduction to Database System in NYCU in 2021 Spring.

## Requirements
- PHP
- MariaDB / MySQL

## DB Schema
- db_project
  - employee_shop
    - employee_id: int(20)
    - shop_id: int(20)
  - orders
    - **OID**: int(20)
      - auto increment
    - status: varchar(10)
    - created_time: datetime
    - completed_time: datetime
      - allow null value
    - order_maker_id: int(20)
    - completer_id: int(20)
      - allow null value
    - shop_id: int(20)
    - order_amount: int(11)
    - order_price: int(11)
  - shops
    - **SID**: int(20)
      - auto increment
    - shopkeeper_id: int(20)
    - shop_name: varchar(40)
    - city: varchar(40)
    - mask_price: int(11)
    - mask_amount: int(11)
  - users
    - **UID**: int(20)
      - auto increment
    - username: char(40)
    - password: varchar(64)
    - salt: char(4)
    - phone_num: char(10)

## Preview
### 首頁：
![](https://raw.githubusercontent.com/Alan-Kuan/2021Spring-DB-Project/master/screenshots/preview1.png)

### 資訊主頁：
![](https://raw.githubusercontent.com/Alan-Kuan/2021Spring-DB-Project/master/screenshots/preview2.png)

### 店家列表：
![](https://raw.githubusercontent.com/Alan-Kuan/2021Spring-DB-Project/master/screenshots/preview3.png)

### 我的訂單：
![](https://raw.githubusercontent.com/Alan-Kuan/2021Spring-DB-Project/master/screenshots/preview4.png)

### 店家管理：
![](https://raw.githubusercontent.com/Alan-Kuan/2021Spring-DB-Project/master/screenshots/preview5.png)

### 店家訂單：
![](https://raw.githubusercontent.com/Alan-Kuan/2021Spring-DB-Project/master/screenshots/preview6.png)
