// Tutorial page functionality
// Tutorial page functionality

// Progress indicator
function initProgressIndicator() {
    // Create progress container and bar if they don't exist
    if (!document.querySelector('.progress-container')) {
        const progressContainer = document.createElement('div');
        progressContainer.className = 'progress-container';
        
        const progressBar = document.createElement('div');
        progressBar.className = 'progress-bar';
        
        progressContainer.appendChild(progressBar);
        document.body.appendChild(progressContainer);
    }
    
    // Update progress bar as user scrolls
    window.addEventListener('scroll', function() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.querySelector('.progress-bar').style.width = scrolled + '%';
    });
}

// Smooth scroll to section when clicking on TOC links
function initSmoothScroll() {
    document.querySelectorAll('.toc-list a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            window.scrollTo({
                top: targetElement.offsetTop - 100,
                behavior: 'smooth'
            });
            
            // Update URL hash without jumping
            history.pushState(null, null, targetId);
        });
    });
}

// Highlight current section in TOC based on scroll position
function initScrollSpy() {
    const sections = document.querySelectorAll('.tutorial-section');
    const tocLinks = document.querySelectorAll('.toc-list a');
    
    window.addEventListener('scroll', function() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop - 150) {
                current = '#' + section.getAttribute('id');
            }
        });
        
        tocLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === current) {
                link.classList.add('active');
            }
        });
    });
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initProgressIndicator();
    initSmoothScroll();
    initScrollSpy();
    
    // Add active class to TOC links on hover
    document.querySelectorAll('.toc-list a').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        link.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
});
