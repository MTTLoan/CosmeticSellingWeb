<a name="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">

  <h3 align="center">CHAPTER ONE - WEBSITE BÁN SÁCH</h3>

  <p align="center">
    Website thương mại điện tử đáng tin cậy trong việc cung cấp sách!
    <br />
    <a href="https://lle858756.wixsite.com/chapterone"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://drive.google.com/drive/folders/1fWcr6jsW1oO4czN8uNXsK3rE8B2KylZX?usp=sharing">View Demo</a>
    ·
    <a href="https://github.com/MTTLoan/CosmeticsSellingWeb/issues">Request Feature</a>
  </p>
</div>

## Thông tin nhóm

- Nhóm trưởng: Mai Thị Thanh Loan - 22520782 - [Github](https://github.com/MTTLoan)
- Nhóm phó: Lê Hồng Ngọc Linh - 22520761
- Thành viên: Trần Nguyễn Bảo Hoàng - 22520478
- Thành viên: Lê Thiên Kim - 22520728
- **Giáo viên hướng dẫn: Tạ Việt Phương**

<!-- ABOUT THE PROJECT -->

## Tổng quan về dự án

[![Product Name Screen Shot][product-screenshot]](https://github.com/MTTLoan/DeadlineBeaters)

Website thương mại điện tử cho nhà sách – Chapter One giúp người dùng dễ dàng tìm kiếm, đồng thời giúp quản lý và điều hành nhanh và hiệu quả hơn. Website không chỉ mang lại trải nghiệm mua sắm tiện lợi mà còn hỗ trợ quản lý hiệu quả cho nhà sách.

**Tính năng**

- Mua hàng
- Quản lý sách và danh mục sách
- Quản lý giỏ hàng
- Quản lý đơn hàng và hóa đơn
- Báo cáo thống kê doanh thu và sản phẩm bán chạy

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->

## Bắt đầu

Bạn cần phải cài đặt Laravel, Composer và MySQL!

- [Laravel](https://laravel.com/docs/)
- [Composer](https://getcomposer.org/)
- [MySQL](https://dev.mysql.com/downloads/)

### Cài đặt

1. Tải source code về:
    ```sh
    git clone https://github.com/MTTLoan/DeadlineBeaters.git
    ```
2. Cài đặt Composer trong thư mục dự án:
    ```sh
    composer install
    ```
3. Tạo tệp .env trong thư mục gốc và sao chép nội dung từ tệp .env.example. Sau đó cập nhật các thông tin cần thiết như database:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=qlthuexe
    DB_USERNAME=root
    DB_PASSWORD=yourpassword
    ```
4. Chạy các lệnh sau để khởi tạo database và seed data:
    ```sh
    php artisan migrate
    php artisan db:seed
    ```
5. Khởi động server:
    ```sh
    php artisan serve
    ```
6. Truy cập website tại `http://localhost:8000`.

Note: Tài khoản admin mặc định: `admin/123456`

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTRIBUTING -->

## Đóng góp

Đóng góp là điều làm cho cộng đồng mã nguồn mở trở thành một nơi tuyệt vời để học hỏi, truyền cảm hứng và sáng tạo. Mọi đóng góp của bạn đều được **đánh giá cao**.

Nếu bạn có gợi ý nào có thể làm cho dự án tốt hơn, hãy fork kho lưu trữ và tạo một pull request. Bạn cũng có thể đơn giản là mở một vấn đề với thẻ “enhancement”.

Đụng quên đánh dấu sao cho dự án! Cảm ơn bạn một lần nữa!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTACT -->

## Liên hệ

Mai Thị Thanh Loan (Nhóm trưởng) - [@MTTLoan](https://github.com/MTTLoan)

EMAIL: CosmeticsSellingWeb@gmail.com

Project Link: [https://github.com/MTTLoan/CosmeticsSellingWeb](https://github.com/MTTLoan/CosmeticsSellingWeb)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->

[product-screenshot]: assets/DemoFlows.gif
