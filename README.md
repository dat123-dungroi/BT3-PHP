# PTIT CRM - Hệ thống Quản lý Khách hàng

Dự án web CRM (Customer Relationship Management) xây dựng bằng PHP thuần, sử dụng cơ sở dữ liệu **MySQL trên Cloud (Aiven)** 
---

## ⚙️ Yêu cầu hệ thống

| Thành phần | Yêu cầu |
|---|---|
| PHP | Phiên bản **7.4** trở lên |
| Kết nối Internet | **Bắt buộc** (để kết nối Cloud MySQL) |

---

## 🚀 Hướng dẫn cài đặt & khởi động

### Bước 1: Tải PHP (nếu chưa có)

1. Truy cập: https://www.php.net/downloads
2. Tải bản **Windows x64 Non Thread Safe** (file `.zip`)
3. Giải nén vào ổ đĩa, ví dụ: `C:\php`
4. Thêm `C:\php` vào **System PATH**:
   - Nhấn **Windows + R** → gõ `sysdm.cpl` → Enter
   - Chọn tab **Advanced** → **Environment Variables**
   - Ở phần **User variables** → tìm **Path** → nhấn **Edit** → **New**
   - Paste vào đường dẫn PHP (ví dụ: `C:\php`) → **OK**
5. Mở terminal mới, kiểm tra bằng lệnh:
   ```
   php -v
   ```
   Nếu hiện ra version PHP là thành công ✅

> 💡 **Nếu máy đã cài XAMPP**: PHP nằm sẵn trong thư mục XAMPP (ví dụ: `D:\xampp\php`), thêm đường dẫn đó vào PATH là được.

---

### Bước 2: Tải dự án từ GitHub
tên github: dat123-dungroi
mk github: dat165236@gmail.com
```bash
git clone https://github.com/<tên-tài-khoản>/<tên-repo>.git
```

Hoặc tải file ZIP trên GitHub → giải nén ra.

---

### Bước 3: Khởi động dự án

Mở **Terminal / PowerShell / Command Prompt**, chạy lệnh:

```bash
php -S localhost:8000 -t đường-dẫn-thư-mục-dự-án
```

**Ví dụ cụ thể:**
```bash
php -S localhost:8000 -t C:\Users\Ten\Downloads\BT3
```

Hoặc `cd` vào thư mục dự án trước rồi chạy:
```bash
cd C:\Users\Ten\Downloads\BT3
php -S localhost:8000
```

---

### Bước 4: Mở trình duyệt

Sau khi terminal hiện thông báo `Development Server started`, mở trình duyệt và truy cập:

```
http://localhost:8000
```

---

## 🔑 Tài khoản đăng nhập mặc định

| Role | Tên đăng nhập | Mật khẩu |
|---|---|---|
| Quản trị viên | `admin` | `123456` |
| Nhân viên Sale | `sale1` | `123456` |

---

## 🗄️ Cơ sở dữ liệu
Tạo .env trong folder: 
type nul > .env
notepad .env

Dự án sử dụng **Cloud MySQL (Aiven)** — dữ liệu được lưu trữ trực tuyến, tự động kết nối khi có Internet.

- Không cần cài đặt MySQL
- Không cần tạo database
- Không cần import file SQL

> ⚠️ **Lưu ý**: Máy tính cần có **kết nối Internet** để dự án hoạt động. Nếu mất mạng, trang web sẽ báo lỗi kết nối database.

---

## 📁 Cấu trúc thư mục

```
BT3/
├── config/
│   └── db.php              # Cấu hình kết nối Cloud MySQL
├── includes/
│   ├── header.php
│   └── footer.php
├── modules/
│   ├── dashboard/          # Trang tổng quan
│   ├── customers/          # Quản lý khách hàng
│   ├── interactions/       # Lịch sử tương tác
│   └── users/              # Quản lý nhân viên
├── assets/                 # CSS, JS, hình ảnh
├── index.php               # Trang chủ (điều hướng)
└── login.php               # Trang đăng nhập
```

---

## 🛠️ Công nghệ sử dụng

- **Backend**: PHP 7.4+
- **Database**: MySQL 8.4 trên Cloud (Aiven.io)
- **Frontend**: Bootstrap 5
- **Kết nối DB**: PDO (PHP Data Objects)

DB_HOST=ptit-crm-datto2580-1773.a.aivencloud.com
DB_NAME=defaultdb
DB_USER=avnadmin
DB_PASSWORD=AVNS_7sT_gBfTNTS5fRj7hZj
DB_PORT=11770
DB_CHARSET=utf8mb4
