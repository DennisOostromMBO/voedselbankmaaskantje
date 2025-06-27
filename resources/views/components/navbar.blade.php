<!-- Custom Application Navbar -->
<header class="header">
    <nav class="navbar container">
        <a href="{{ route('dashboard') }}" class="logo">
            <i class="fas fa-utensils"></i>
            Voedselbank Maaskantje
        </a>

        <!-- Hamburger Menu Button -->
        <button class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#" class="nav-link"><i class="fas fa-users"></i> Klanten</a></li>
            <li><a href="{{ route('food-parcels.index') }}" class="nav-link {{ request()->routeIs('food-parcels.*') ? 'active' : '' }}"><i class="fas fa-box"></i> Voedselpakketten</a></li>
            <li><a href="#" class="nav-link"><i class="fas fa-warehouse"></i> Voorraad</a></li>
            <li><a href="#" class="nav-link"><i class="fas fa-truck"></i> Leveranciers</a></li>
            @auth
                <li><a href="{{ route('profile.edit') }}" class="nav-link"><i class="fas fa-user-circle"></i> Profiel</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Uitloggen
                        </a>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i> Inloggen</a></li>
            @endauth
        </ul>
    </nav>
</header>

<style>
:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --info-color: #3b82f6;
    --light-color: #f8fafc;
    --dark-color: #1f2937;
    --border-color: #e5e7eb;
    --text-primary: #111827;
    --text-secondary: #6b7280;
    --bg-light: #f9fafb;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --border-radius: 0.5rem;
    --transition: all 0.3s ease;
}

/* Header Styles */
.header {
    background: var(--primary-color);
    box-shadow: var(--shadow-lg);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
    text-decoration: none;
    transition: var(--transition);
}

.logo:hover {
    transform: scale(1.05);
}

.logo i {
    font-size: 1.8rem;
}

.nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 1rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    color: white;
    text-decoration: none;
    border-radius: var(--border-radius);
    transition: var(--transition);
    font-weight: 500;
}

.nav-link:hover,
.nav-link.active {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.nav-link i {
    font-size: 1rem;
}

/* Hamburger Menu */
.hamburger {
    display: none;
    flex-direction: column;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
}

.hamburger span {
    width: 25px;
    height: 3px;
    background: white;
    margin: 3px 0;
    transition: var(--transition);
    border-radius: 2px;
}

.hamburger.active span:nth-child(1) {
    transform: rotate(-45deg) translate(-5px, 6px);
}

.hamburger.active span:nth-child(2) {
    opacity: 0;
}

.hamburger.active span:nth-child(3) {
    transform: rotate(45deg) translate(-5px, -6px);
}

/* Mobile Styles */
@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        position: relative;
    }

    .hamburger {
        display: flex;
    }

    .nav-links {
        display: none;
        width: 100%;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        background: var(--primary-color);
        padding: 1rem 2rem;
        box-shadow: var(--shadow-lg);
        border-radius: 0 0 var(--border-radius) var(--border-radius);
    }

    .nav-links.mobile-open {
        display: flex;
    }

    .nav-link {
        justify-content: flex-start;
        width: 100%;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link:last-child {
        border-bottom: none;
    }
}

@media (max-width: 480px) {
    .navbar {
        padding: 1rem;
    }

    .logo {
        font-size: 1.25rem;
    }

    .logo i {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('mobile-open');
        });

        // Close mobile menu when clicking on a nav link
        const navLinksItems = navLinks.querySelectorAll('.nav-link');
        navLinksItems.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navLinks.classList.remove('mobile-open');
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!hamburger.contains(event.target) && !navLinks.contains(event.target)) {
                hamburger.classList.remove('active');
                navLinks.classList.remove('mobile-open');
            }
        });
    }
});
</script>
