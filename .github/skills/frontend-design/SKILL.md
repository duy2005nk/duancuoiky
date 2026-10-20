---
name: frontend-design
description: 'Sử dụng khi tạo hoặc thiết kế lại giao diện web có tính nhận diện riêng cho mọi sản phẩm và công nghệ frontend, bao gồm định hướng thị giác, typography, màu sắc, bố cục, responsive, accessibility, nội dung, chuyển động, triển khai và đánh giá thiết kế.'
---

# Thiết kế giao diện frontend

## Vai trò

Đóng vai trò trưởng nhóm thiết kế thị giác dựa trên chính sản phẩm. Tạo giao diện có bản sắc phù hợp với chủ đề, đối tượng, nội dung và nhiệm vụ chính thay vì áp dụng một template SaaS, dashboard, landing page hoặc thư viện component mặc định.

## Khám phá chủ đề

Trước khi thiết kế, xác định rõ:

- Sản phẩm là gì.
- Ai sẽ sử dụng sản phẩm.
- Người dùng cần hoàn thành việc gì.
- Bối cảnh và điều kiện sử dụng.
- Giao diện cần truyền tải những phẩm chất nào.
- Nội dung thật và những từ ngữ bắt buộc phải xuất hiện.

Nếu thông tin quan trọng chưa có trong brief, hãy đề xuất một chủ đề, đối tượng và trường hợp sử dụng cụ thể. Trình bày ngắn gọn giả định đó trước khi phát triển thiết kế.

## Tạo hướng thiết kế thị giác

Lập kế hoạch trước khi viết mã triển khai. Tạo một định hướng ngắn gồm các phần sau.

### Màu sắc

Định nghĩa 4-6 màu có tên, mã hex và vai trò rõ ràng:

- nền trang và bề mặt
- chữ chính
- hành động hoặc điểm nhấn
- dữ liệu phụ
- trạng thái thành công, cảnh báo và lỗi nếu cần

Mỗi màu phải có mục đích. Không thêm màu chỉ để trang trí.

### Typography

Chọn một hoặc hai font phù hợp với ngôn ngữ và nền tảng triển khai. Phân vai trò cho từng font, ví dụ:

- tiêu đề trang hoặc tiêu đề nổi bật
- nội dung chính
- điều hướng
- nhãn và dữ liệu

Thiết lập thang chữ rõ ràng với kích thước, độ đậm, chiều cao dòng và khoảng cách có chủ đích. Typography phải tạo cấu trúc, hỗ trợ đọc hiểu và thiết lập thứ bậc thay vì chỉ đóng vai trò trung tính.

### Bố cục

Mô tả cấu trúc trang, mật độ, căn chỉnh, cách nhóm nội dung và hành động chính bằng một hoặc hai câu.

Nếu bố cục là trọng tâm, hãy so sánh các phương án bằng wireframe ASCII đơn giản. Chọn cách căn chỉnh và mật độ thông tin dựa trên nhiệm vụ chính của người dùng.

### Nguyên tắc thiết kế

Đưa ra 3-5 nguyên tắc khiến hướng thiết kế phù hợp riêng với sản phẩm. Các nguyên tắc này cần định hướng quyết định cụ thể về thứ bậc, mật độ, chất liệu, hình ảnh, tương tác hoặc giọng điệu.

## Kiểm tra tính riêng biệt

Trước khi triển khai, xem lại hướng thiết kế và đặt câu hỏi:

- Ảnh chụp màn hình này có thể thuộc về một sản phẩm khác không?
- Những lựa chọn nào xuất phát từ chủ đề, đối tượng, nội dung hoặc nhiệm vụ?
- Có một ý tưởng thị giác đáng nhớ thay vì trang trí ở mọi khu vực không?
- Border, nhãn, đường chia, đánh số và card có truyền đạt thông tin hay chỉ trang trí không?
- Mỗi lựa chọn có giúp nhận diện, hiểu nội dung, điều hướng hoặc thao tác tốt hơn không?
- Hướng thiết kế có đang biến thành một mẫu thời thượng mặc định không?

Thay các lựa chọn chung chung bằng phương án gắn với sản phẩm. Luôn ưu tiên yêu cầu cụ thể trong brief kể cả khi một số cách nhìn giống xu hướng thị giác phổ biến.

## Hướng dẫn thị giác

- Xây bố cục dựa trên thứ bậc thông tin thay vì một lưới card trang trí đồng nhất.
- Chỉ dùng hình ảnh, texture, minh họa hoặc gradient khi chúng có liên hệ rõ với chủ đề.
- Để một phần tử mang trọng tâm mạnh nhất; giữ các khu vực xung quanh có kỷ luật và yên tĩnh.
- Dùng khoảng trắng, kích thước, tương phản, mật độ và căn chỉnh để tạo thứ bậc.
- Tránh tô màu riêng lẻ một từ vô lý, nhãn viết cách rộng chỉ để trang trí, đánh số cho nội dung không có trình tự và chuỗi meta lặp lại không truyền tải thông tin.
- Không lặp hiệu ứng xuất hiện ở mọi khu vực của trang.
- Ưu tiên sự tiết chế có chủ đích thay vì lấp đầy không gian bằng hiệu ứng.

## Nội dung và giọng điệu

Viết copy giao diện từ góc nhìn người dùng.

- Dùng ngôn ngữ rõ ràng, quen thuộc với đối tượng.
- Đặt tên hành động theo kết quả mà người dùng nhận được.
- Giữ tên hành động nhất quán trong cả luồng.
- Giải thích trạng thái tải, trống, thành công, cảnh báo và lỗi một cách cụ thể.
- Khi thao tác thất bại, nói rõ điều gì xảy ra và người dùng cần làm gì tiếp theo.
- Tránh lời hứa mơ hồ, nội dung lấp đầy, nhãn không cần thiết và copy mang tính trang trí.
- Phù hợp giọng điệu với sản phẩm và đối tượng.

## Tương tác và chuyển động

Chỉ dùng chuyển động khi nó truyền đạt thay đổi hoặc hỗ trợ tương tác.

- Giữ chuyển cảnh ngắn và có mục đích.
- Chỉ dùng chuyển động nổi bật cho sự kiện trang quan trọng hoặc thao tác người dùng.
- Tôn trọng `prefers-reduced-motion`.
- Không làm chậm tương tác hoặc che khuất phản hồi bằng animation.
- Giữ trạng thái tải, vô hiệu, focus, hover, chọn, thành công và lỗi nhất quán.

## Thiết kế responsive và accessibility

Thiết kế theo thiết bị và bối cảnh sử dụng thực tế của sản phẩm.

- Kiểm tra bố cục trên mobile hẹp, tablet và desktop.
- Giữ độ dài dòng và vùng chạm dễ đọc.
- Ngăn tràn nội dung mà không che khuất thông tin quan trọng.
- Sử dụng cấu trúc ngữ nghĩa và control native khi phù hợp.
- Có trạng thái keyboard focus rõ ràng.
- Không dùng màu làm tín hiệu duy nhất cho trạng thái.
- Đảm bảo tương phản đủ cho chữ, control, border và trạng thái focus.
- Bảo toàn khả năng zoom và phóng to chữ của trình duyệt.
- Quan tâm đến ngôn ngữ và hệ thống chữ được sản phẩm sử dụng.

## Triển khai

Tuân theo công nghệ, quy ước component, cấu hình build, cách viết style và ngôn ngữ thiết kế hiện có trong dự án.

- Đọc các trang, component, style, script và tài nguyên liên quan trước khi sửa.
- Tái sử dụng primitive hiện có trước khi tạo mới.
- Giữ nguyên hành vi và nội dung dự định.
- Đặt quyết định thị giác trong lớp component hoặc style phù hợp.
- Không nhập giả định từ framework hoặc dự án khác.
- Viết selector rõ ràng và tránh xung đột specificity.
- Load font, icon, hình ảnh và script theo quy ước của dự án.
- Chỉ thêm dependency khi công nghệ hiện có không đáp ứng được yêu cầu.

## Quy trình hai lượt

1. Phân tích brief và tạo hướng thiết kế thị giác.
2. Đánh giá hướng đó để tìm lựa chọn chung chung, sau đó chỉnh sửa.
3. Triển khai trải nghiệm hoàn chỉnh nhỏ nhất bằng công nghệ hiện có của dự án.
4. Đối chiếu kết quả đã render với hướng thiết kế.
5. Bỏ những chi tiết trang trí không hỗ trợ nhận diện, hiểu nội dung, điều hướng hoặc thao tác.

## Tự đánh giá cuối

Trước khi hoàn thành, trả lời:

- Giao diện có dễ hiểu ngay lập tức không?
- Nó có hỗ trợ người dùng hoàn thành nhiệm vụ chính không?
- Thứ bậc thị giác có chủ đích thay vì mang tính trang trí không?
- Mỗi phần tử lặp lại có thực sự cần thiết không?
- Có một ý tưởng thị giác đáng nhớ và có chủ đích không?
- Có chi tiết nào riêng cho sản phẩm và đối tượng không?
- Giao diện có dùng được khi truy cập bằng bàn phím, reduced motion và viewport hẹp không?

Bỏ mọi thứ không góp phần đạt các mục tiêu trên.

## Xác minh

Trước khi hoàn thành:

- Chạy các lệnh lint, build và test có sẵn trong dự án.
- Kiểm tra trang đã render trong môi trường development hoặc production của dự án.
- Kiểm tra bố cục mobile, tablet và desktop.
- Thử điều hướng bằng bàn phím và các trạng thái tương tác quan trọng.
- Kiểm tra trạng thái trống, tải, thành công, cảnh báo, lỗi và validation.
- Xem console, overflow, tài nguyên bị lỗi và các vấn đề accessibility.
- Đảm bảo phần triển khai tuân thủ quy ước của framework hiện tại thay vì giả định từ dự án khác.
