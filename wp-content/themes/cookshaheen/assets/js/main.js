/**
 * CookShaheen Main JavaScript
 * 
 * @package CookShaheen
 */

document.addEventListener('DOMContentLoaded', function () {

    // Navbar scroll effect
    const navbar = document.getElementById('navbar');

    function handleScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Check initial state

    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navLinks = document.getElementById('navLinks');

    if (mobileToggle && navLinks) {
        mobileToggle.addEventListener('click', function () {
            navLinks.classList.toggle('active');
            mobileToggle.classList.toggle('active');
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const navHeight = navbar.offsetHeight;
                const targetPosition = targetElement.offsetTop - navHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });

                // Close mobile menu if open
                if (navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                    mobileToggle.classList.remove('active');
                }
            }
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.cs-feature-card, .cs-recipe-card, .cs-testimonial-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Add animate-in class styles
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);

    // Newsletter form handling with AJAX
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const email = document.getElementById('subscriberEmail').value;
            const button = document.getElementById('subscribeBtn');
            const messageDiv = document.getElementById('newsletterMessage');
            const originalText = button.innerHTML;

            // Disable button during submission
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';

            // Send AJAX request
            const formData = new FormData();
            formData.append('action', 'cookshaheen_newsletter');
            formData.append('email', email);
            formData.append('nonce', cookshaheen_ajax.nonce);

            fetch(cookshaheen_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    messageDiv.style.display = 'block';

                    if (data.success) {
                        messageDiv.innerHTML = '<p style="color: #fff; font-size: 1.1rem;">✅ ' + data.data.message + '</p>';
                        button.innerHTML = '<i class="fas fa-check"></i> Subscribed!';
                        button.style.background = '#27ae60';
                        newsletterForm.reset();

                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.style.background = '';
                            button.disabled = false;
                            messageDiv.style.display = 'none';
                        }, 5000);
                    } else {
                        messageDiv.innerHTML = '<p style="color: #fff; font-size: 1.1rem;">❌ ' + data.data.message + '</p>';
                        button.innerHTML = originalText;
                        button.disabled = false;

                        setTimeout(() => {
                            messageDiv.style.display = 'none';
                        }, 5000);
                    }
                })
                .catch(error => {
                    console.error('Newsletter subscription error:', error);
                    messageDiv.style.display = 'block';
                    messageDiv.innerHTML = '<p style="color: #fff;">❌ An error occurred. Please try again.</p>';
                    button.innerHTML = originalText;
                    button.disabled = false;

                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                });
        });
    }

    // Counter animation for stats
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);

        function updateCounter() {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start).toLocaleString() + '+';
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString() + '+';
            }
        }

        updateCounter();
    }

    // Observe stats section
    const statsSection = document.querySelector('.cs-about-stats');
    if (statsSection) {
        const statsObserver = new IntersectionObserver(function (entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.cs-stat-number');
                    statNumbers.forEach(stat => {
                        const text = stat.textContent;
                        const number = parseInt(text.replace(/[^0-9]/g, ''));
                        if (text.includes('K')) {
                            animateCounter(stat, number);
                            stat.textContent = number + 'K+';
                        } else {
                            animateCounter(stat, number);
                        }
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        statsObserver.observe(statsSection);
    }

    // Parallax effect for hero section
    const hero = document.querySelector('.cs-hero');
    if (hero) {
        window.addEventListener('scroll', function () {
            const scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                hero.style.backgroundPositionY = scrolled * 0.5 + 'px';
            }
        });
    }

    // Recipe Print Handler
    const printButtons = document.querySelectorAll('.wprm-recipe-print, [data-wprm-recipe-print]');
    printButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Get the recipe element (closest .wprm-recipe or fallback to recipe container)
            const recipe = this.closest('.wprm-recipe') || document.querySelector('.wprm-recipe');

            if (recipe) {
                // Create a new window for printing
                const printWindow = window.open('', '', 'width=900,height=1000');
                const printContent = recipe.cloneNode(true);

                // Add print styles
                const printStyles = `
                    <style>
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }
                        
                        body {
                            font-family: 'Poppins', Arial, sans-serif;
                            line-height: 1.6;
                            color: #000;
                            background: #fff;
                            padding: 20px;
                        }
                        
                        .wprm-recipe {
                            max-width: 100%;
                            border: 1px solid #ddd;
                            padding: 20px;
                        }
                        
                        .wprm-recipe-title {
                            font-size: 24px;
                            font-weight: bold;
                            margin-bottom: 15px;
                            color: #000;
                        }
                        
                        .wprm-recipe-meta,
                        .wprm-recipe-summary {
                            margin-bottom: 15px;
                            font-size: 14px;
                        }
                        
                        .wprm-recipe-image {
                            text-align: center;
                            margin: 20px 0;
                        }
                        
                        .wprm-recipe-image img {
                            max-width: 100%;
                            height: auto;
                        }
                        
                        .wprm-recipe-ingredients-header,
                        .wprm-recipe-instructions-header {
                            font-size: 16px;
                            font-weight: bold;
                            margin-top: 20px;
                            margin-bottom: 10px;
                            color: #000;
                        }
                        
                        .wprm-recipe-ingredient,
                        .wprm-recipe-instruction {
                            margin-bottom: 8px;
                            line-height: 1.6;
                        }
                        
                        .wprm-recipe-ingredient:before {
                            content: "• ";
                            margin-right: 8px;
                        }
                        
                        .wprm-recipe-instruction {
                            margin-left: 20px;
                        }
                        
                        /* Hide interactive elements */
                        .wprm-recipe-actions,
                        .wprm-recipe-buttons,
                        .wprm-recipe-print,
                        .wprm-recipe-pin,
                        button,
                        a {
                            display: none !important;
                        }
                        
                        @media print {
                            body {
                                margin: 0;
                                padding: 0;
                            }
                        }
                    </style>
                `;

                // Remove action buttons from cloned content
                const buttons = printContent.querySelectorAll('.wprm-recipe-actions, .wprm-recipe-buttons, .wprm-recipe-print, .wprm-recipe-pin, button');
                buttons.forEach(btn => btn.remove());

                // Write content to print window
                printWindow.document.write('<!DOCTYPE html>');
                printWindow.document.write('<html>');
                printWindow.document.write('<head>');
                printWindow.document.write('<meta charset="UTF-8">');
                printWindow.document.write('<title>' + (printContent.querySelector('.wprm-recipe-title')?.innerText || 'Recipe') + '</title>');
                printWindow.document.write(printStyles);
                printWindow.document.write('</head>');
                printWindow.document.write('<body>');
                printWindow.document.write(printContent.innerHTML);
                printWindow.document.write('</body>');
                printWindow.document.write('</html>');
                printWindow.document.close();

                // Trigger print dialog after content loads
                setTimeout(() => {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                }, 250);
            } else {
                // Fallback to browser print function
                window.print();
            }
        });
    });

    console.log('🍳 CookShaheen loaded successfully!');
});
