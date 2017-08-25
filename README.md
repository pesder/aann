# aann -- Another ANN 公告系統
## 源起
因為轉換到 php7.0 之後，原本的 ann 公告系統己經過於老舊，除了必須改用 mysqli 之外還有許多 php 錯誤訊息。原本使用的 ann 不但是 utf-8 版，還是 Nansen Su [修正的版本](https://bitbucket.org/nansenat16/ann)，仍然無法正常運作，因此就決定重寫一個相容的系統。
## 原則
* 基於舊 ANN 系統 UTF-8 版的資料庫表格
* 使用框架來架速開發程序
* 大多數設定值改以資料庫儲存
* 整合較新使用的技術
## 實作功能
* 公告列表
* 閱讀公告介面
* 編輯公告(含附件、相關網址)
* 刪除公告
* 密碼驗證，整合最原始的明文密碼、Nansen Su 使用的 sha1 加密，最新則採 php 的 password_hash 功能
* 新增、編輯、刪除處室
* 新增、編輯、刪除組員
* 搜尋公文
* 顯示處室公文
* RSS feed
## 新增功能
* 網站設定值設定介面
* 停用組員功能
* Openid(雲林縣) 登入功能
* ATOM feed
## 版權
* ci-feed：作者 Roumen Damianoff <roumen@dawebs.com> 採 MIT 授權
* LightOpenID：作者 Mewp 採 MIT 授權
* 本程式主要編寫部分位於 application 下的 controllers , models , views 中，以 GPL v3 授權