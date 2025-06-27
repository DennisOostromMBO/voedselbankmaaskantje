<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Application - Professional Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar container">
            <a href="#" class="logo">
                <i class="fas fa-utensils"></i>
                Voedselbank Maaskantje
            </a>
            <ul class="nav-links">
                <li><a href="#" class="nav-link active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#" class="nav-link"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="#" class="nav-link"><i class="fas fa-box"></i> Food Parcels</a></li>
                <li><a href="#" class="nav-link"><i class="fas fa-warehouse"></i> Stock</a></li>
                <li><a href="#" class="nav-link"><i class="fas fa-truck"></i> Suppliers</a></li>
                <li><a href="#" class="nav-link"><i class="fas fa-user-circle"></i> Profile</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Stats Dashboard -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">1,247</div>
                    <div class="stat-label">Total Customers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">89</div>
                    <div class="stat-label">Active Parcels</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">456</div>
                    <div class="stat-label">Items in Stock</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">23</div>
                    <div class="stat-label">Suppliers</div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <h1 class="card-title">Customer Management</h1>
                        <p class="card-subtitle">Manage all your customers and their information</p>
                    </div>
                    <div class="btn-group">
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Customer
                        </a>
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-download"></i>
                            Export
                        </a>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="search-container">
                    <div class="form-group search-input">
                        <label class="form-label">Search Customers</label>
                        <input type="text" class="form-control" placeholder="Search by name, email, or phone...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-control form-select">
                            <option>All Statuses</option>
                            <option>Active</option>
                            <option>Inactive</option>
                            <option>Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-user"></i> Name</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-phone"></i> Phone</th>
                                <th><i class="fas fa-calendar"></i> Registration</th>
                                <th><i class="fas fa-chart-line"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#001</td>
                                <td>
                                    <div class="d-flex align-center gap-1">
                                        <i class="fas fa-user-circle" style="color: var(--primary-color);"></i>
                                        John Doe
                                    </div>
                                </td>
                                <td>john.doe@example.com</td>
                                <td>+31 6 12345678</td>
                                <td>Jan 15, 2024</td>
                                <td><span class="badge badge-success">Active</span></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>
                                    <div class="d-flex align-center gap-1">
                                        <i class="fas fa-user-circle" style="color: var(--success-color);"></i>
                                        Jane Smith
                                    </div>
                                </td>
                                <td>jane.smith@example.com</td>
                                <td>+31 6 87654321</td>
                                <td>Feb 3, 2024</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#003</td>
                                <td>
                                    <div class="d-flex align-center gap-1">
                                        <i class="fas fa-user-circle" style="color: var(--danger-color);"></i>
                                        Mike Johnson
                                    </div>
                                </td>
                                <td>mike.johnson@example.com</td>
                                <td>+31 6 11223344</td>
                                <td>Mar 10, 2024</td>
                                <td><span class="badge badge-danger">Inactive</span></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <a href="#">‹ Previous</a>
                    <span class="current">1</span>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#">5</a>
                    <a href="#">Next ›</a>
                </div>
            </div>

            <!-- Form Example Card -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Add New Customer</h2>
                    <p class="card-subtitle">Fill in the information below to add a new customer</p>
                </div>

                <form>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" placeholder="Enter last name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control" placeholder="Enter email address" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" placeholder="Enter full address">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control form-select">
                                <option>Active</option>
                                <option>Pending</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Registration Date</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i>
                            Save Customer
                        </button>
                        <button type="reset" class="btn btn-secondary btn-lg">
                            <i class="fas fa-undo"></i>
                            Reset Form
                        </button>
                        <a href="#" class="btn btn-outline btn-lg">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Alert Examples -->
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Customer has been successfully added to the system!
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Please verify the customer's email address before proceeding.
            </div>

            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                Error: Unable to save customer. Please check all required fields.
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Tip: You can bulk import customers using the CSV upload feature.
            </div>
        </div>
    </main>

    <script>
        // Simple JavaScript for interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'var(--light-bg)';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });

            // Add click effects to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 300);
                });
            });
        });
    </script>

    <style>
        /* Ripple effect for buttons */
        .btn {
            position: relative;
            overflow: hidden;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.3s ease-out;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>
</body>
</html>
