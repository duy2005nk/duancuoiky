---
name: frontend
description: Chuyên gia thiết kế và lập trình giao diện Frontend chuyên nghiệp, ứng dụng Tailwind CSS, Alpine.js/Vanilla JS, Atomic Design, bảo mật XSS và các hiệu ứng chuyển động mượt mà (micro-interactions) cho nút bấm, thẻ card và form.
---

# Pro Frontend Development Skill

Skill này định hướng tiêu chuẩn viết code Frontend nhằm đảm bảo giao diện trực quan, tốc độ phản hồi nhanh, tính thẩm mỹ cao và an toàn bảo mật tuyệt đối.

## 1. Cấu trúc dự án & Atomic Design
- **Page Templates:** `public/*.php` (Trang giao diện chính, tách biệt logic PHP và phần hiển thị HTML).
- **Atomic Components:** 
  - `public/components/atoms/`: Các thành phần nhỏ nhất (button, input, badge, label).
  - `public/components/molecules/`: Kết hợp atom thành khối chức năng nhỏ (form group, search box, card item).
  - `public/components/organisms/`: Hoàn thiện các phần giao diện lớn (order form section, navigation bar, data table).

## 2. Tiêu chuẩn thiết kế & Micro-Interactions (Hiệu ứng chuyển động)
Mọi tương tác của người dùng phải mang lại cảm giác phản hồi tức thì (tactile feedback):
- **Nút bấm (Buttons):**
  - Luôn có chuyển động mượt: `transition-all duration-200 ease-in-out`
  - Hiệu ứng Hover: Đổi màu nhẹ, nâng bóng đổ (`hover:shadow-md hover:-translate-y-0.5`).
  - Hiệu ứng Click (Active): Cảm giác lún xuống (`active:translate-y-0 active:scale-95`).
- **Thẻ sản phẩm / Khung chứa (Cards):**
  - Hiệu ứng nổi lên khi rê chuột: `transition-all duration-300 hover:shadow-xl hover:-translate-y-1`
- **Trạng thái tải (Loading States):**
  - Nút bấm khi xử lý API phải chuyển sang `disabled`, mờ nhẹ (`opacity-75`), kèm biểu tượng xoay (`animate-spin`).

## 3. Quy chuẩn kỹ thuật PHP & Bảo mật
- **Chống XSS:** Luôn escape dữ liệu động xuất ra HTML bằng hàm `htmlspecialchars($data, ENT_QUOTES, 'UTF-8')`.
- **Semantic HTML:** Sử dụng đúng thẻ ngữ nghĩa (`<header>`, `<main>`, `<section>`, `<article>`, `<form>`, `<label>`).
- **Tránh trùng lặp ID:** Đảm bảo các thành phần render động không bị trùng lặp `id` trên cùng một DOM.

## 4. CSS & Responsive Design (Tailwind CSS)
- Ưu tiên sử dụng utility classes sẵn có của Tailwind, giữ phong cách màu sắc nhất quán (dải màu `slate` cho nền/chữ, kết hợp màu nhận diện thương hiệu).
- Thiết kế hướng từ màn hình nhỏ lên lớn (`mobile-first`), sử dụng linh hoạt các breakpoint `sm:`, `md:`, `lg:` để giao diện không bị tràn viền.

## 5. JavaScript / Alpine.js & Xử lý API
- Xử lý bất đồng bộ qua `fetch` API, luôn đặt trong khối `try/catch/finally` để khôi phục trạng thái nút bấm khi hoàn tất hoặc gặp lỗi mạng.
- Validate dữ liệu cơ bản ở phía client trước khi gửi lên server.
- Sử dụng `textContent` hoặc các template an toàn, tuyệt đối không dùng `innerHTML` với dữ liệu không đáng tin cậy.

## 6. Quy trình thực thi (Workflow)
1. Xác định chính xác file giao diện và thành phần Atomic cần chỉnh sửa.
2. Xây dựng cấu trúc HTML/Tailwind kèm theo đầy đủ hiệu ứng `transition`, `hover`, `active`.
3. Tích hợp logic Alpine.js hoặc JS thuần để xử lý sự kiện tương tác và gọi API.
4. Kiểm tra các trạng thái: `loading`, `success`, `error`, `empty` và khả năng điều hướng bằng bàn phím (accessibility).
5. Xác thực không có lỗi cú pháp hoặc lỗ hổng bảo mật trước khi kết thúc.