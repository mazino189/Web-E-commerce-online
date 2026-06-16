const categoryFallbacks: Record<string, string> = {
  'laptops-computers': 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600',
  'smartphones-tablets': 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600',
  'audio-speakers': 'https://images.unsplash.com/photo-1543510473-ac2c35329a28?w=600',
  'wearables-smartwatches': 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600',
  'gaming-gear': 'https://images.unsplash.com/photo-1600080972464-8e5f358024af?w=600',
  'cameras-photography': 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600',
  'accessories': 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600',
  'peripherals': 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600',
  'kitchen-appliances': 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=600',
  'cookware': 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=600',
  'tableware': 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=600',
  'baking-tools': 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600',
  'food-storage': 'https://images.unsplash.com/photo-1594911774802-8822a707caff?w=600'
};

const genericFallback = 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=600';

export function getCategoryFallback(categorySlug?: string | null): string {
  if (categorySlug && categoryFallbacks[categorySlug]) {
    return categoryFallbacks[categorySlug];
  }
  return genericFallback;
}

export function productImage(src: string | undefined | null, categorySlug?: string | null): string {
  if (src && src.startsWith('http')) return src;
  return getCategoryFallback(categorySlug);
}

export function handleImageError(e: React.SyntheticEvent<HTMLImageElement, Event>, categorySlug?: string | null): void {
  const img = e.currentTarget;
  if (img.src !== getCategoryFallback(categorySlug)) {
    img.src = getCategoryFallback(categorySlug);
  }
}
