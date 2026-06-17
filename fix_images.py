import os
import re

seeder_path = r'd:\Website Ecomerce online\database\seeders\ProductSeeder.php'
amazon_path = r'd:\Website Ecomerce online\amazon-data-cleaning\scrape_amazon.py'

def fix_seeder():
    with open(seeder_path, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    pattern = re.compile(r"('name'\s*=>\s*'([^']+)',.*?'image'\s*=>\s*')([^']+)(',)")
    for i, line in enumerate(lines):
        match = pattern.search(line)
        if match:
            prefix, name, old_image, suffix = match.groups()
            if "images.unsplash.com" in old_image:
                # Use loremflickr to get a relevant image based on the first word of the product name
                keyword = name.split()[0].lower()
                # To ensure we get distinct images even for the same keyword, we can add a random number parameter or use lock
                # loremflickr supports `lock` query parameter to get the same image for a specific id, or random if we just want random
                # We want distinct but consistent, so lock to index i
                new_image = f"https://loremflickr.com/600/600/{keyword},product?lock={i}"
                new_block = prefix + new_image + suffix
                lines[i] = line.replace(match.group(0), new_block, 1)

    with open(seeder_path, 'w', encoding='utf-8') as f:
        f.writelines(lines)

def fix_amazon():
    with open(amazon_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # The mock data uses an identical Amazon image for all products.
    # We will replace each occurrence with a unique loremflickr image based on the product type.
    
    # We find all occurrences of the old image url and replace them sequentially
    old_img = 'https://m.media-amazon.com/images/I/71yzJoE7WlL._AC_SX679_.jpg'
    
    new_content = content
    i = 100
    while old_img in new_content:
        # In the context of python script, it's either phone or laptop or accessory
        new_img = f"https://loremflickr.com/600/600/electronics?lock={i}"
        new_content = new_content.replace(old_img, new_img, 1)
        i += 1
        
    with open(amazon_path, 'w', encoding='utf-8') as f:
        f.write(new_content)

if __name__ == "__main__":
    fix_seeder()
    fix_amazon()
    print("Replaced images with loremflickr!")
