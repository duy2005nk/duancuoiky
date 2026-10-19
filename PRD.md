# Yêu cầu nghiệp vụ (dự án phần mềm B2B - HKD Trịnh Văn Hùng)
- Lĩnh vực: Giao nhận thực phẩm (đậu phụ, rau củ) cho các nhà ăn công ty.
- Luồng đặt hàng: Khách hàng (Nhà ăn) chọn món và số lượng. 
- Quy tắc Cut-off time: 
  + Nếu đặt trước 18:00: Trạng thái đơn là APPROVED (Đã chốt cho ngày hôm sau).
  + Nếu đặt sau 18:00: Trạng thái đơn là PENDING_APPROVAL (Chờ duyệt).
- Quy tắc giá: Giá lấy từ bảng PRICE_LIST trong DB tại thời điểm đặt hàng, tuyệt đối không lấy từ Client gửi lên.
- Đầu ra Backend: Cấp RESTful API.
