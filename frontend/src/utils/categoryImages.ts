const categoryFallbacks: Record<string, string> = {
  // Computers
  'laptops-computers': 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600',
  'laptops': 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600',
  'computers': 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600',

  // Phones & Tablets
  'smartphones-tablets': 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600',
  'smartphones': 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600',
  'tablets': 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600',

  // Audio
  'audio-speakers': 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600',
  'audio': 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600',
  'speakers': 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600',
  'headphones': 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600',
  'earphones': 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600',

  // Smart devices
  'smart-devices': 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',
  'smart-home': 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',
  'smart': 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',
  'iot': 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',

  // Wearables
  'wearables-smartwatches': 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600',
  'wearables': 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600',
  'smartwatches': 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600',

  // Gaming
  'gaming-gear': 'https://images.unsplash.com/photo-1600080972464-8e5f358024af?w=600',
  'gaming': 'https://images.unsplash.com/photo-1600080972464-8e5f358024af?w=600',

  // Cameras
  'cameras-photography': 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600',
  'cameras': 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600',

  // Accessories
  'accessories': 'https://images.unsplash.com/photo-1625895197185-efcec01cffe0?w=600',
  'peripherals': 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600',
  'networking': 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600',
  'cables': 'https://images.unsplash.com/photo-1625895197185-efcec01cffe0?w=600',
  'chargers': 'https://images.unsplash.com/photo-1625895197185-efcec01cffe0?w=600',

  // Kitchen (legacy)
  'kitchen-appliances': 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=600',
  'cookware': 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=600',
  'tableware': 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=600',
  'baking-tools': 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600',
  'food-storage': 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=600',
};

const genericFallback = 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600';

export function getCategoryFallback(categorySlug?: string | null): string {
  if (categorySlug) {
    // Exact match
    if (categoryFallbacks[categorySlug]) return categoryFallbacks[categorySlug];
    // Partial match — find first key that the slug contains or is contained by
    const slug = categorySlug.toLowerCase();
    for (const [key, url] of Object.entries(categoryFallbacks)) {
      if (slug.includes(key) || key.includes(slug)) return url;
    }
  }
  return genericFallback;
}

export function productImage(src: string | undefined | null, categorySlug?: string | null): string {
  if (src && src.startsWith('http')) return src;
  return getCategoryFallback(categorySlug);
}

export function handleImageError(e: React.SyntheticEvent<HTMLImageElement, Event>, categorySlug?: string | null): void {
  const img = e.currentTarget;
  const fallback = getCategoryFallback(categorySlug);
  if (img.src !== fallback) {
    img.src = fallback;
  }
}
