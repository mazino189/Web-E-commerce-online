import os
import re
import time
from duckduckgo_search import DDGS

seeder_path = r'd:\Website Ecomerce online\database\seeders\ProductSeeder.php'

with open(seeder_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

pattern = re.compile(r"('name'\s*=>\s*'([^']+)',.*?'image'\s*=>\s*')([^']+)(',)")

def replace_images():
    ddgs = DDGS()
    for i, line in enumerate(lines):
        match = pattern.search(line)
        if match:
            prefix, name, old_image, suffix = match.groups()
            if "images.unsplash.com" in old_image:
                print(f"Searching image for: {name}")
                try:
                    results = ddgs.images(name + " product white background", max_results=1)
                    if results and len(results) > 0:
                        new_image = results[0]['image']
                        print(f"  Found: {new_image}")
                        new_block = prefix + new_image + suffix
                        lines[i] = line.replace(match.group(0), new_block, 1)
                        time.sleep(1) # respect rate limits
                    else:
                        print(f"  No results found for {name}")
                except Exception as e:
                    print(f"  Error fetching {name}: {e}")

if __name__ == "__main__":
    replace_images()
    with open(seeder_path, 'w', encoding='utf-8') as f:
        f.writelines(lines)
    print("Finished updating ProductSeeder.php images.")
