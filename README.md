# Devs-Forum
本專題結合知識分享平台的概念，以虛擬點數作為獎勵制度，發展出程式技術與經驗交換之應用。

讓使用者能分享程式碼與發起技術討論，同時在與其他使用
者交流的過程中得到虛擬點數作為獎勵。

## 需求分析(What)
### 功能性需求
- 非會員功能
  1. 查看文章
  2. 搜尋文章
  3. 註冊會員
- 會員功能
    1. 一般會員
        1. 發表與編輯個人文章
        2. 兌換商店獎勵
        3. 購買付費文章
    2. 管理員
        1. 會員管理(禁封)
        2. 討論文章管理
        3. 商店獎勵管理
        4. 查看使用者回報
- 商品
    1. 點數兌換商品
    
### 非功能性需求
1. 資料安全性
2. 系統穩定性
3. 使用者易操作介面(UI/UX)

## 系統完整性限制
### users
- 個人點數須滿足 點數 >= 0
- 會員點數轉讓須滿足 點數 >= 1
- 會員密碼加密前長度須滿足 長度 >= 15
### threads
- 解鎖文章收費須滿足 費用 >= 1
- 發表文章內容與標題須滿足 字數 >=1
- 回覆類型文章費用須滿足 費用 = 0
### products
- 兌換商品數量需滿足 數量 >= 0, 數量 <= 1000
- 兌換商品費用需滿足 費用 >= 0

## ER Diagram
![image](https://user-images.githubusercontent.com/29170077/182735057-a89e03b5-fcd8-45c9-9e83-c1cadec6026e.png)

## DEMO
![image](https://user-images.githubusercontent.com/29170077/182735360-afabda7a-ad02-42b2-89ef-ba7f42cf5e57.png)

![image](https://user-images.githubusercontent.com/29170077/182735392-fd57bf8d-2264-41d9-9230-e0414572a16b.png)

![image](https://user-images.githubusercontent.com/29170077/182735415-e7932151-159e-41f3-83aa-cf56b8bacb22.png)

![image](https://user-images.githubusercontent.com/29170077/182735472-3ddb30a0-899b-4d7a-9d21-aad933f2e953.png)

![image](https://user-images.githubusercontent.com/29170077/182735506-9d12d418-f5c7-433b-8cf7-96dab7e08fbf.png)

![image](https://user-images.githubusercontent.com/29170077/182735635-2c860bb7-f22a-414b-861e-961042161890.png)




