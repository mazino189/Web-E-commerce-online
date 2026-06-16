import pandas as pd
import os
import re
import random


def slugify(text):
    text = str(text).lower()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[\s_-]+', '-', text)
    return text.strip('-')


def categorize_product(name):
    name_lower = name.lower()
    if any(kw in name_lower for kw in ['headphones', 'earbuds']):
        return 3
    elif any(kw in name_lower for kw in ['macbook', 'laptop', 'monitor']):
        return 2
    elif any(kw in name_lower for kw in ['iphone', 'ipad', 'tablet']) or re.search(r'\bphone\b', name_lower):
        return 1
    elif 'sound' in name_lower:
        return 3
    else:
        return 4


def assign_brand(name):
    name_lower = name.lower()
    if 'apple' in name_lower or 'iphone' in name_lower:
        return 1
    elif 'samsung' in name_lower:
        return 2
    elif 'sony' in name_lower:
        return 3
    elif 'bose' in name_lower:
        return 4
    else:
        return 5


BASE_DIR = os.path.dirname(os.path.abspath(__file__))
INPUT_FILE = os.path.join(BASE_DIR, "raw_data", "Updated_sales.csv")
OUTPUT_FILE = os.path.join(BASE_DIR, "cleaned_data", "products.csv")

if not os.path.exists(INPUT_FILE):
    print(f"Loi: Khong tim thay file du lieu tho tai: {INPUT_FILE}")
    exit(1)

print("Dang doc file du lieu tho Updated_sales.csv...")
df = pd.read_csv(INPUT_FILE)

print(f"So dong giao dich: {len(df)}")
print(f"Cac cot: {list(df.columns)}")

df = df.dropna(subset=['Product'])

unique_products = df.groupby('Product', as_index=False).agg({
    'Price Each': 'min',
    'Quantity Ordered': 'sum',
})

print(f"So luong san pham duy nhat: {len(unique_products)}")

print("Dang don dep va anh xa cau truc bang cho Laravel...")
df_clean = pd.DataFrame()

df_clean['name'] = unique_products['Product'].astype(str).str.strip().str.slice(0, 255)

df_clean['slug'] = df_clean['name'].apply(slugify)

df_clean['description'] = df_clean['name'].apply(
    lambda x: f"High-quality {x.lower()} \u2014 perfect for your everyday needs. Durable, reliable, and built to last."
)

raw_price = unique_products['Price Each'].astype(str).str.replace(r'[^\d.]', '', regex=True)
df_clean['price'] = pd.to_numeric(raw_price, errors='coerce').fillna(9.99).astype(float)

df_clean['category_id'] = df_clean['name'].apply(categorize_product)
df_clean['brand_id'] = df_clean['name'].apply(assign_brand)

random.seed(42)
df_clean['stock'] = [random.randint(10, 100) for _ in range(len(df_clean))]

os.makedirs(os.path.join(BASE_DIR, "cleaned_data"), exist_ok=True)

df_clean = df_clean.sort_values('name').reset_index(drop=True)

df_clean.to_csv(OUTPUT_FILE, index=False)

print("\nDON DEP DU LIEU THANH CONG!")
print("Tong so san pham thu duoc:", len(df_clean))
print("Cot co trong file CSV moi:", list(df_clean.columns))
print("Da luu tai:", OUTPUT_FILE)
