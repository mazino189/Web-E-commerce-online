import pandas as pd
import os
import re
import random

# Hàm tự động tạo Slug từ tên sản phẩm tiếng Anh
def slugify(text):
    text = str(text).lower()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[\s_-]+', '-', text)
    return text.strip('-')

# =========================
# CONFIG PATHS
# =========================
INPUT_FILE = "raw_data/amazon1.csv"
OUTPUT_FILE = "cleaned_data/products.csv"

if not os.path.exists(INPUT_FILE):
    print(f"Lỗi: Không tìm thấy file dữ liệu thô tại: {INPUT_FILE}")
    exit(1)

# Đọc trực tiếp file CSV thô, bỏ qua hoàn toàn file Notebook
print("Đang đọc file dữ liệu thô amazon.csv...")
df_raw = pd.read_csv(INPUT_FILE)

print("Đang dọn dẹp và ánh xạ cấu trúc bảng cho Laravel...")
df_clean = pd.DataFrame()

# 1. Ánh xạ Cột Name (Giới hạn tối đa 255 ký tự cho VARCHAR Laravel)
df_clean["name"] = df_raw["product_name"].astype(str).str.strip().str.slice(0, 255)

# 2. Tự động tạo Cột Slug
df_clean["slug"] = df_clean["name"].apply(slugify)

# 3. Ánh xạ Cột Description (Lấy từ about_product)
df_clean["description"] = df_raw["about_product"].fillna("Sản phẩm đồ gia dụng nhà bếp chất lượng cao.").astype(str).str.strip()

# 4. Làm sạch và quy đổi Cột Price sang VND
# (Giá gốc là Rupee Ấn Độ, ví dụ ₹399. Chúng ta nhân với 300đ để quy đổi sang VND cho thực tế, ví dụ: ~120,000đ)
raw_price = df_raw["discounted_price"].astype(str).str.replace(r'[^\d.]', '', regex=True)
numeric_price = pd.to_numeric(raw_price, errors="coerce").fillna(500).astype(int)
df_clean["price"] = numeric_price * 300 

# 5. Tự tạo Cột Stock (Số lượng tồn kho ngẫu nhiên từ 10 đến 100)
df_clean["stock"] = [random.randint(10, 100) for _ in range(len(df_clean))]

# 6. Tự tạo Cột Category ID (Gán ngẫu nhiên ID danh mục từ 1 đến 5)
df_clean["category_id"] = [random.randint(1, 5) for _ in range(len(df_clean))]

# 7. Tự tạo Cột Brand ID (Gán ngẫu nhiên ID thương hiệu từ 1 đến 5)
df_clean["brand_id"] = [random.randint(1, 5) for _ in range(len(df_clean))]

# Loại bỏ các dòng bị trống tên sản phẩm
df_clean = df_clean.dropna(subset=["name"])
df_clean = df_clean[df_clean["name"] != ""]

# Xuất ra file CSV sạch cuối cùng
os.makedirs("cleaned_data", exist_ok=True)
df_clean.to_csv(OUTPUT_FILE, index=False)

print("\nDỌN DẸP DỮ LIỆU THÀNH CÔNG! 😎🔥")
print("Tổng số sản phẩm thu được:", len(df_clean))
print("Cột có trong file CSV mới:", list(df_clean.columns))
print("Đã lưu tại:", OUTPUT_FILE)