/**
 * Custom Carousel - Smooth scrolling with mouse wheel and arrow navigation
 * Supports responsive column classes: cols-sm-1, cols-md-2, cols-lg-6, cols-xl-6, cols-xxl-8, etc.
 * Supports data-show-arrows attribute: "always" (default) or "hover" (fade in/out on mouse)
 * Shows peek of next/previous items: 10% at start/end, 5% in the middle
 */

class CustomCarousel {
  constructor(element) {
    this.container = element;
    this.inner = element.querySelector('.carousel-custom-inner');
    this.prevBtn = element.querySelector('.carousel-custom-prev');
    this.nextBtn = element.querySelector('.carousel-custom-next');
    this.items = element.querySelectorAll('.carousel-custom-item');
    this.showArrows = element.getAttribute('data-show-arrows') || 'always';
    
    if (!this.inner || this.items.length === 0) return;
    
    this.itemsPerView = this.getItemsPerView();
    this.itemWidth = 0;
    this.gap = 16; // 1rem in pixels
    this.arrowFadeInOut = this.showArrows === 'hover'; // Only fade in/out if showArrows is 'hover'
    
    this.init();
  }
  
  init() {
    // Set initial button state based on data-show-arrows
    if (this.arrowFadeInOut) {
      this.container.setAttribute('data-show-arrows', 'false');
    }
    
    // Calculate item dimensions
    this.updateDimensions();
    
    // Bind events
    if (this.prevBtn) this.prevBtn.addEventListener('click', () => this.scroll(-1));
    if (this.nextBtn) this.nextBtn.addEventListener('click', () => this.scroll(1));
    
    // Mouse wheel horizontal scroll
    this.inner.addEventListener('wheel', (e) => this.handleWheel(e), { passive: false });
    
    // Scroll event to update peek state and button states
    this.inner.addEventListener('scroll', () => {
      this.updatePeekState();
      this.updateButtonStates();
    });
    
    // Mouse hover for arrow visibility
    if (this.arrowFadeInOut) {
      this.container.addEventListener('mouseenter', () => this.showArrowsWithFade());
      this.container.addEventListener('mouseleave', () => this.hideArrowsWithFade());
    }
    
    // Update on resize
    window.addEventListener('resize', () => {
      this.itemsPerView = this.getItemsPerView();
      this.updateDimensions();
      this.updateButtonStates();
      this.updatePeekState();
    });
    
    // Initial button and peek state
    this.updateButtonStates();
    this.updatePeekState();
  }
  
  showArrowsWithFade() {
    this.container.setAttribute('data-show-arrows', 'true');
  }
  
  hideArrowsWithFade() {
    this.container.setAttribute('data-show-arrows', 'false');
  }
  
  getItemsPerView() {
    // Determine items per view based on responsive classes and window width
    const classList = this.container.className;
    const width = window.innerWidth;
    
    // Check for base classes first (apply to all screen sizes)
    if (classList.includes('carousel-custom-cols-8')) return 8;
    if (classList.includes('carousel-custom-cols-7')) return 7;
    if (classList.includes('carousel-custom-cols-6')) return 6;
    if (classList.includes('carousel-custom-cols-5')) return 5;
    if (classList.includes('carousel-custom-cols-4')) return 4;
    if (classList.includes('carousel-custom-cols-3')) return 3;
    if (classList.includes('carousel-custom-cols-2')) return 2;
    if (classList.includes('carousel-custom-cols-1')) return 1;
    
    // Default: 1 item for mobile
    if (width < 768) return 1; // sm
    
    // md: 768px
    if (width < 992) {
      if (classList.includes('carousel-custom-cols-md-6')) return 6;
      if (classList.includes('carousel-custom-cols-md-5')) return 5;
      if (classList.includes('carousel-custom-cols-md-4')) return 4;
      if (classList.includes('carousel-custom-cols-md-3')) return 3;
      if (classList.includes('carousel-custom-cols-md-2')) return 2;
      return 1;
    }
    
    // lg: 992px
    if (width < 1200) {
      if (classList.includes('carousel-custom-cols-lg-6')) return 6;
      if (classList.includes('carousel-custom-cols-lg-5')) return 5;
      if (classList.includes('carousel-custom-cols-lg-4')) return 4;
      if (classList.includes('carousel-custom-cols-lg-3')) return 3;
      if (classList.includes('carousel-custom-cols-lg-2')) return 2;
      return 1;
    }
    
    // xl: 1200px
    if (width < 1400) {
      if (classList.includes('carousel-custom-cols-xl-6')) return 6;
      if (classList.includes('carousel-custom-cols-xl-5')) return 5;
      if (classList.includes('carousel-custom-cols-xl-4')) return 4;
      if (classList.includes('carousel-custom-cols-xl-3')) return 3;
      if (classList.includes('carousel-custom-cols-xl-2')) return 2;
      return 1;
    }
    
    // xxl: 1400px+
    if (classList.includes('carousel-custom-cols-xxl-8')) return 8;
    if (classList.includes('carousel-custom-cols-xxl-7')) return 7;
    if (classList.includes('carousel-custom-cols-xxl-6')) return 6;
    if (classList.includes('carousel-custom-cols-xxl-5')) return 5;
    if (classList.includes('carousel-custom-cols-xxl-4')) return 4;
    if (classList.includes('carousel-custom-cols-xxl-3')) return 3;
    if (classList.includes('carousel-custom-cols-xxl-2')) return 2;
    
    return 1;
  }
  
  updateDimensions() {
    if (this.items.length === 0) return;
    
    // Calculate item width based on container and items per view
    const containerWidth = this.inner.offsetWidth;
    const totalGap = (this.itemsPerView - 1) * this.gap;
    this.itemWidth = (containerWidth - totalGap) / this.itemsPerView;
  }
  
  updatePeekState() {
    // Determine if carousel is at start, end, or middle
    const isAtStart = this.inner.scrollLeft <= 10;
    const isAtEnd = this.inner.scrollLeft >= (this.inner.scrollWidth - this.inner.offsetWidth - 10);
    const isInMiddle = !isAtStart && !isAtEnd;
    
    // Update peek class based on position
    if (isInMiddle) {
      this.container.classList.add('carousel-custom-peek-middle');
    } else {
      this.container.classList.remove('carousel-custom-peek-middle');
    }
  }
  
  scroll(direction) {
    // Scroll by one "page" (itemsPerView items)
    const scrollAmount = (this.itemWidth + this.gap) * this.itemsPerView;
    const newScrollLeft = this.inner.scrollLeft + (direction * scrollAmount);
    
    this.inner.scrollTo({
      left: newScrollLeft,
      behavior: 'smooth'
    });
    
    // Update button states after scroll completes (smooth scroll ~500ms)
    setTimeout(() => this.updateButtonStates(), 500);
  }
  
  handleWheel(e) {
    // Allow horizontal scroll with mouse wheel
    if (e.deltaY !== 0) {
      e.preventDefault();
      
      // Scroll amount proportional to wheel delta
      const scrollAmount = Math.sign(e.deltaY) * 100;
      const newScrollLeft = this.inner.scrollLeft + scrollAmount;
      
      this.inner.scrollLeft = newScrollLeft;
    }
  }
  
  updateButtonStates() {
    if (!this.prevBtn || !this.nextBtn) return;
    
    const isAtStart = this.inner.scrollLeft <= 0;
    const isAtEnd = this.inner.scrollLeft >= (this.inner.scrollWidth - this.inner.offsetWidth - 10);
    
    this.prevBtn.disabled = isAtStart;
    this.nextBtn.disabled = isAtEnd;
  }
}

// Initialize all custom carousels on page load
document.addEventListener('DOMContentLoaded', () => {
  const carousels = document.querySelectorAll('.carousel-custom');
  carousels.forEach(carousel => new CustomCarousel(carousel));
});
