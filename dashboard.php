<?php
session_start();
if (!isset($_SESSION['auth']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$section = isset($_GET['section']) ? htmlspecialchars($_GET['section'], ENT_QUOTES, 'UTF-8') : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Home Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Energy Monitoring Color Palette */
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --bg-dark: #1a1d23;
            --bg-card: rgba(255, 255, 255, 0.95);

            /* Energy Colors */
            --color-efficient: #10b981; /* Green - efficiency, normal operation */
            --color-reliable: #3b82f6; /* Blue - reliability, water, metrics */
            --color-solar: #f59e0b; /* Orange/Yellow - solar, warmth */
            --color-alert: #ef4444; /* Red - high consumption, alerts */
            --color-teal: #06b6d4; /* Teal - modern, futuristic */
            --color-success: #22c55e;
            --color-warning: #fbbf24;

            /* Neutral Colors */
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --border-color: #e5e7eb;

            /* Sidebar Colors */
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #3b82f6;

            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --gradient-teal: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 30px 20px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .logo-section {
            margin-bottom: 40px;
            text-align: center;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-section h1 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .logo-section p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 300;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 8px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255, 255, 255, 0.7);
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .nav-menu a i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-menu a:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(5px);
        }

        .nav-menu a.active {
            background: var(--gradient-primary);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            border-left: 3px solid #ffffff;
        }

        .logout-btn {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn a {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        .logout-btn a:hover {
            background: var(--gradient-danger);
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 30px;
            min-height: 100vh;
        }

        /* Top Bar */
        .topbar {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .topbar-left h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-primary);
        }

        .topbar-left p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 300;
        }

        .profile-box {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--bg-primary);
            padding: 10px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .profile-box:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .profile-info strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 3px;
            color: var(--text-primary);
        }

        .profile-info small {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Content Sections */
        .content-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .card:nth-child(1)::before {
            background: var(--gradient-success);
        }

        .card:nth-child(2)::before {
            background: var(--gradient-warning);
        }

        .card:nth-child(3)::before {
            background: var(--gradient-primary);
        }

        .card:nth-child(4)::before {
            background: var(--gradient-danger);
        }

        .card:nth-child(5)::before {
            background: var(--gradient-teal);
        }

        .card:nth-child(6)::before {
            background: var(--gradient-success);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #ffffff;
        }

        .card:nth-child(1) .card-icon {
            background: var(--gradient-success);
        }

        .card:nth-child(2) .card-icon {
            background: var(--gradient-warning);
        }

        .card:nth-child(3) .card-icon {
            background: var(--gradient-primary);
        }

        .card:nth-child(4) .card-icon {
            background: var(--gradient-danger);
        }

        .card:nth-child(5) .card-icon {
            background: var(--gradient-teal);
        }

        .card:nth-child(6) .card-icon {
            background: var(--gradient-success);
        }

        .card-title {
            font-size: 0.95rem;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 15px 0;
            color: var(--text-primary);
        }

        .card-footer {
            font-size: 0.85rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Appliance Grid */
        .appliance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .appliance-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .appliance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .appliance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .appliance-icon {
            font-size: 2.5rem;
            color: var(--color-reliable);
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-switch input:disabled + .toggle-slider {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: 0.3s;
            border-radius: 30px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        input:checked + .toggle-slider {
            background: var(--gradient-success);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(30px);
        }

        .appliance-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-primary);
        }

        .appliance-status {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .appliance-power {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .power-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 5px;
            color: var(--color-efficient);
        }

        .auto-mode-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .auto-mode-label {
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
        }

        .auto-mode-toggle {
            position: relative;
            width: 50px;
            height: 26px;
        }

        .auto-mode-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .auto-mode-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: 0.3s;
            border-radius: 26px;
        }

        .auto-mode-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .auto-mode-toggle input:checked + .auto-mode-slider {
            background: var(--gradient-primary);
        }

        .auto-mode-toggle input:checked + .auto-mode-slider:before {
            transform: translateX(24px);
        }

        .auto-mode-active {
            color: var(--color-efficient) !important;
        }

        /* Table Styles */
        .table-container {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border-color);
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            border-bottom: 2px solid var(--border-color);
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            color: var(--text-secondary);
        }

        tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: var(--bg-primary);
        }

        td {
            color: var(--text-primary);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-on {
            background: rgba(16, 185, 129, 0.15);
            color: var(--color-efficient);
        }

        .status-off {
            background: rgba(107, 114, 128, 0.15);
            color: var(--text-secondary);
        }

        /* Chart Container */
        .chart-container {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border-color);
            margin-bottom: 25px;
            min-height: 350px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            color: var(--text-primary);
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 320px;
            background: var(--bg-primary);
            border-radius: 12px;
            padding: 20px;
        }

        .chart-svg {
            width: 100%;
            height: 100%;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }

        /* Settings Form */
        .settings-section {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border-color);
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .settings-section h3 {
            margin-bottom: 20px;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--text-primary);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--color-reliable);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-group input::placeholder {
            color: var(--text-light);
        }

        .btn {
            padding: 12px 30px;
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        /* Grid lines for charts */
        .grid-line {
            stroke: var(--border-color);
            stroke-width: 1;
            stroke-dasharray: 5,5;
        }

        .axis-label {
            fill: var(--text-secondary);
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
        }

        .axis-title {
            fill: var(--text-primary);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-section">
                <h1><i class="fas fa-home"></i> SMART HOME</h1>
                <p>Control Center</p>
            </div>

            <ul class="nav-menu">
                <li><a href="?section=dashboard" class="<?php echo $section === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a></li>
                <li><a href="?section=appliances" class="<?php echo $section === 'appliances' ? 'active' : ''; ?>">
                    <i class="fas fa-lightbulb"></i>
                    <span>Appliances</span>
                </a></li>
                <li><a href="?section=energy" class="<?php echo $section === 'energy' ? 'active' : ''; ?>">
                    <i class="fas fa-bolt"></i>
                    <span>Energy Monitor</span>
                </a></li>
                <li><a href="?section=summary" class="<?php echo $section === 'summary' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Summary</span>
                </a></li>
                <li><a href="?section=logs" class="<?php echo $section === 'logs' ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i>
                    <span>Activity Logs</span>
                </a></li>
                <li><a href="?section=settings" class="<?php echo $section === 'settings' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a></li>
                <li class="logout-btn"><a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <div class="topbar-left">
                    <h2><?php
                        $titles = [
                            'dashboard' => 'Dashboard Overview',
                            'appliances' => 'Appliances Control',
                            'energy' => 'Energy Monitor',
                            'summary' => 'Usage Summary',
                            'logs' => 'Activity Logs',
                            'settings' => 'System Settings'
                        ];
                        echo $titles[$section] ?? 'Dashboard';
                    ?></h2>
                    <p>Welcome back, <?php echo $username; ?>!</p>
                </div>
                <div class="profile-box">
                    <div class="profile-avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                    <div class="profile-info">
                        <strong><?php echo $username; ?></strong>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>

            <!-- Dashboard Section -->
            <div class="content-section <?php echo $section === 'dashboard' ? 'active' : ''; ?>" id="dashboard">
                <div class="cards-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-user-circle"></i></div>
                            <div class="card-title">Live PIR</div>
                        </div>
                        <div class="card-value" id="pir-value">245</div>
                        <div class="card-footer">
                            <i class="fas fa-arrow-up"></i> Motion detected
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-sun"></i></div>
                            <div class="card-title">LDR Value</div>
                        </div>
                        <div class="card-value" id="ldr-value">780</div>
                        <div class="card-footer">
                            <i class="fas fa-info-circle"></i> Lux level
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-bolt"></i></div>
                            <div class="card-title">Current</div>
                        </div>
                        <div class="card-value" id="current-value">12.5 A</div>
                        <div class="card-footer">
                            <i class="fas fa-chart-line"></i> Real-time
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-tachometer-alt"></i></div>
                            <div class="card-title">Wattage</div>
                        </div>
                        <div class="card-value" id="watt-value">2.75 kW</div>
                        <div class="card-footer">
                            <i class="fas fa-plug"></i> Total power
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="card-title">Daily Cost</div>
                        </div>
                        <div class="card-value" id="cost-day">$8.45</div>
                        <div class="card-footer">
                            <i class="fas fa-calendar-day"></i> Today's usage
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-chart-bar"></i></div>
                            <div class="card-title">Monthly Estimate</div>
                        </div>
                        <div class="card-value" id="cost-month">$253</div>
                        <div class="card-footer">
                            <i class="fas fa-calendar-alt"></i> Projected cost
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appliances Section -->
            <div class="content-section <?php echo $section === 'appliances' ? 'active' : ''; ?>" id="appliances">
                <div class="appliance-grid">
                    <div class="appliance-card">
                        <div class="appliance-header">
                            <div class="appliance-icon"><i class="fas fa-lightbulb"></i></div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="light-toggle" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="appliance-name">Living Room Light</div>
                        <div class="appliance-status">Status: <span class="status-badge status-on">ON</span></div>
                        <div class="appliance-power">
                            Power Consumption: <span class="power-value">120W</span>
                        </div>
                        <div class="auto-mode-section">
                            <div class="auto-mode-label">
                                <i class="fas fa-robot"></i>
                                <span id="light-auto-label">Auto Mode</span>
                            </div>
                            <label class="auto-mode-toggle">
                                <input type="checkbox" id="light-auto-toggle">
                                <span class="auto-mode-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="appliance-card">
                        <div class="appliance-header">
                            <div class="appliance-icon"><i class="fas fa-fan"></i></div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="fan-toggle">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="appliance-name">Ceiling Fan</div>
                        <div class="appliance-status">Status: <span class="status-badge status-off">OFF</span></div>
                        <div class="appliance-power">
                            Power Consumption: <span class="power-value">0W</span>
                        </div>
                        <div class="auto-mode-section">
                            <div class="auto-mode-label">
                                <i class="fas fa-robot"></i>
                                <span id="fan-auto-label">Auto Mode</span>
                            </div>
                            <label class="auto-mode-toggle">
                                <input type="checkbox" id="fan-auto-toggle" checked>
                                <span class="auto-mode-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="appliance-card">
                        <div class="appliance-header">
                            <div class="appliance-icon"><i class="fas fa-tv"></i></div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="appliance-name">Smart TV</div>
                        <div class="appliance-status">Status: <span class="status-badge status-on">ON</span></div>
                        <div class="appliance-power">
                            Power Consumption: <span class="power-value">150W</span>
                        </div>
                    </div>

                    <div class="appliance-card">
                        <div class="appliance-header">
                            <div class="appliance-icon"><i class="fas fa-snowflake"></i></div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="appliance-name">Air Conditioner</div>
                        <div class="appliance-status">Status: <span class="status-badge status-on">ON</span></div>
                        <div class="appliance-power">
                            Power Consumption: <span class="power-value">2000W</span>
                        </div>
                    </div>

                    <div class="appliance-card">
                        <div class="appliance-header">
                            <div class="appliance-icon"><i class="fas fa-fire"></i></div>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="appliance-name">Water Heater</div>
                        <div class="appliance-status">Status: <span class="status-badge status-off">OFF</span></div>
                        <div class="appliance-power">
                            Power Consumption: <span class="power-value">0W</span>
                        </div>
                    </div>

                    <div class="appliance-card">
                        <div class="appliance-header">
                            <div class="appliance-icon"><i class="fas fa-laptop"></i></div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="appliance-name">Computer</div>
                        <div class="appliance-status">Status: <span class="status-badge status-on">ON</span></div>
                        <div class="appliance-power">
                            Power Consumption: <span class="power-value">180W</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Energy Monitor Section -->
            <div class="content-section <?php echo $section === 'energy' ? 'active' : ''; ?>" id="energy">
                <div class="cards-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-bolt"></i></div>
                            <div class="card-title">Current Usage</div>
                        </div>
                        <div class="card-value">2.75 kW</div>
                        <div class="card-footer">
                            <i class="fas fa-clock"></i> Real-time monitoring
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="card-title">Peak Today</div>
                        </div>
                        <div class="card-value">3.2 kW</div>
                        <div class="card-footer">
                            <i class="fas fa-arrow-up"></i> At 2:30 PM
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="card-title">Today's Cost</div>
                        </div>
                        <div class="card-value">$8.45</div>
                        <div class="card-footer">
                            <i class="fas fa-calendar-day"></i> Daily total
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <h3 class="chart-title"><i class="fas fa-chart-area"></i> Energy Usage Chart (24 Hours)</h3>
                    <div class="chart-wrapper">
                        <svg class="chart-svg" viewBox="0 0 900 300" id="energy-chart">
                            <defs>
                                <linearGradient id="energyGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.6" />
                                    <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0.05" />
                                </linearGradient>
                                <filter id="shadow">
                                    <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.3"/>
                                </filter>
                            </defs>

                            <!-- Grid lines -->
                            <g class="grid">
                                <line x1="80" y1="40" x2="80" y2="240" class="grid-line"/>
                                <line x1="80" y1="240" x2="820" y2="240" class="grid-line"/>
                                <line x1="80" y1="190" x2="820" y2="190" class="grid-line"/>
                                <line x1="80" y1="140" x2="820" y2="140" class="grid-line"/>
                                <line x1="80" y1="90" x2="820" y2="90" class="grid-line"/>
                                <line x1="80" y1="40" x2="820" y2="40" class="grid-line"/>
                            </g>

                            <!-- Y-axis labels -->
                            <text x="70" y="245" class="axis-label" text-anchor="end">0</text>
                            <text x="70" y="195" class="axis-label" text-anchor="end">1</text>
                            <text x="70" y="145" class="axis-label" text-anchor="end">2</text>
                            <text x="70" y="95" class="axis-label" text-anchor="end">3</text>
                            <text x="70" y="45" class="axis-label" text-anchor="end">4</text>
                            <text x="30" y="145" class="axis-title" text-anchor="middle" transform="rotate(-90, 30, 145)">kW</text>

                            <!-- Area under curve -->
                            <path d="M80,220 L180,200 L280,180 L380,160 L480,170 L580,150 L680,165 L780,155 L820,150 L820,240 L80,240 Z"
                                  fill="url(#energyGradient)"/>

                            <!-- Energy line path with shadow -->
                            <path id="energy-line"
                                  d="M80,220 L180,200 L280,180 L380,160 L480,170 L580,150 L680,165 L780,155 L820,150"
                                  fill="none"
                                  stroke="#3b82f6"
                                  stroke-width="3"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  filter="url(#shadow)"/>

                            <!-- Data points -->
                            <circle cx="80" cy="220" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="180" cy="200" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="280" cy="180" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="380" cy="160" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="480" cy="170" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="580" cy="150" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="680" cy="165" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="780" cy="155" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>
                            <circle cx="820" cy="150" r="5" fill="#ffffff" stroke="#3b82f6" stroke-width="3"/>

                            <!-- X-axis labels -->
                            <text x="80" y="265" class="axis-label" text-anchor="middle">00:00</text>
                            <text x="180" y="265" class="axis-label" text-anchor="middle">03:00</text>
                            <text x="280" y="265" class="axis-label" text-anchor="middle">06:00</text>
                            <text x="380" y="265" class="axis-label" text-anchor="middle">09:00</text>
                            <text x="480" y="265" class="axis-label" text-anchor="middle">12:00</text>
                            <text x="580" y="265" class="axis-label" text-anchor="middle">15:00</text>
                            <text x="680" y="265" class="axis-label" text-anchor="middle">18:00</text>
                            <text x="780" y="265" class="axis-label" text-anchor="middle">21:00</text>
                            <text x="820" y="265" class="axis-label" text-anchor="middle">24:00</text>
                            <text x="450" y="290" class="axis-title" text-anchor="middle">Time</text>
                        </svg>
                    </div>
                </div>

                <div class="chart-container">
                    <h3 class="chart-title"><i class="fas fa-chart-pie"></i> Usage by Appliance</h3>
                    <div class="chart-wrapper">
                        <svg class="chart-svg" viewBox="0 0 500 300" id="pie-chart">
                            <defs>
                                <filter id="pie-shadow">
                                    <feDropShadow dx="0" dy="3" stdDeviation="5" flood-opacity="0.2"/>
                                </filter>
                            </defs>

                            <!-- Pie chart segments with proper angles -->
                            <!-- Air Conditioner (73% - 262.8 degrees) - Green for efficiency -->
                            <path d="M 250 150 L 250 50 A 100 100 0 1 1 353 101 Z"
                                  fill="#10b981"
                                  stroke="#ffffff"
                                  stroke-width="3"
                                  filter="url(#pie-shadow)"/>

                            <!-- Computer (7% - 25.2 degrees) - Teal -->
                            <path d="M 250 150 L 353 101 A 100 100 0 0 1 345 130 Z"
                                  fill="#06b6d4"
                                  stroke="#ffffff"
                                  stroke-width="3"
                                  filter="url(#pie-shadow)"/>

                            <!-- Smart TV (5% - 18 degrees) - Blue -->
                            <path d="M 250 150 L 345 130 A 100 100 0 0 1 327 155 Z"
                                  fill="#3b82f6"
                                  stroke="#ffffff"
                                  stroke-width="3"
                                  filter="url(#pie-shadow)"/>

                            <!-- Light (4% - 14.4 degrees) - Orange/Solar -->
                            <path d="M 250 150 L 327 155 A 100 100 0 0 1 310 175 Z"
                                  fill="#f59e0b"
                                  stroke="#ffffff"
                                  stroke-width="3"
                                  filter="url(#pie-shadow)"/>

                            <!-- Fan (3% - 10.8 degrees) - Purple -->
                            <path d="M 250 150 L 310 175 A 100 100 0 0 1 293 192 Z"
                                  fill="#8b5cf6"
                                  stroke="#ffffff"
                                  stroke-width="3"
                                  filter="url(#pie-shadow)"/>

                            <!-- Others (8%) - Grey -->
                            <path d="M 250 150 L 293 192 A 100 100 0 0 1 250 50 Z"
                                  fill="#6b7280"
                                  stroke="#ffffff"
                                  stroke-width="3"
                                  filter="url(#pie-shadow)"/>

                            <!-- Center circle for donut effect -->
                            <circle cx="250" cy="150" r="60" fill="#f8f9fa" stroke="#ffffff" stroke-width="3"/>
                            <text x="250" y="140" fill="#1f2937" font-size="16" font-weight="600" text-anchor="middle" font-family="Poppins">Total</text>
                            <text x="250" y="165" fill="#3b82f6" font-size="20" font-weight="700" text-anchor="middle" font-family="Poppins">2.75 kW</text>
                        </svg>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #10b981;"></div>
                            <span>Air Conditioner (73%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #06b6d4;"></div>
                            <span>Computer (7%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #3b82f6;"></div>
                            <span>Smart TV (5%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f59e0b;"></div>
                            <span>Light (4%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #8b5cf6;"></div>
                            <span>Fan (3%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #6b7280;"></div>
                            <span>Others (8%)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="content-section <?php echo $section === 'summary' ? 'active' : ''; ?>" id="summary">
                <div class="cards-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-calendar-day"></i></div>
                            <div class="card-title">Today</div>
                        </div>
                        <div class="card-value">8.45 kWh</div>
                        <div class="card-footer">
                            <i class="fas fa-dollar-sign"></i> Cost: $8.45
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-calendar-week"></i></div>
                            <div class="card-title">This Week</div>
                        </div>
                        <div class="card-value">59.2 kWh</div>
                        <div class="card-footer">
                            <i class="fas fa-dollar-sign"></i> Cost: $59.20
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="card-title">This Month</div>
                        </div>
                        <div class="card-value">253 kWh</div>
                        <div class="card-footer">
                            <i class="fas fa-dollar-sign"></i> Cost: $253.00
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <h3 style="margin-bottom: 20px; font-size: 1.3rem; font-weight: 600;">Weekly Summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Usage (kWh)</th>
                                <th>Cost ($)</th>
                                <th>Peak Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Monday</td>
                                <td>8.5</td>
                                <td>$8.50</td>
                                <td>2:30 PM</td>
                            </tr>
                            <tr>
                                <td>Tuesday</td>
                                <td>8.2</td>
                                <td>$8.20</td>
                                <td>3:15 PM</td>
                            </tr>
                            <tr>
                                <td>Wednesday</td>
                                <td>9.1</td>
                                <td>$9.10</td>
                                <td>2:00 PM</td>
                            </tr>
                            <tr>
                                <td>Thursday</td>
                                <td>8.8</td>
                                <td>$8.80</td>
                                <td>2:45 PM</td>
                            </tr>
                            <tr>
                                <td>Friday</td>
                                <td>9.3</td>
                                <td>$9.30</td>
                                <td>1:30 PM</td>
                            </tr>
                            <tr>
                                <td>Saturday</td>
                                <td>7.9</td>
                                <td>$7.90</td>
                                <td>11:00 AM</td>
                            </tr>
                            <tr>
                                <td>Sunday</td>
                                <td>7.4</td>
                                <td>$7.40</td>
                                <td>10:30 AM</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Logs Section -->
            <div class="content-section <?php echo $section === 'logs' ? 'active' : ''; ?>" id="logs">
                <div class="table-container">
                    <h3 style="margin-bottom: 20px; font-size: 1.3rem; font-weight: 600;">Recent Activity</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Action</th>
                                <th>Appliance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10:45 AM</td>
                                <td>Turned ON</td>
                                <td>Living Room Light</td>
                                <td><span class="status-badge status-on">ACTIVE</span></td>
                            </tr>
                            <tr>
                                <td>10:30 AM</td>
                                <td>Turned OFF</td>
                                <td>Ceiling Fan</td>
                                <td><span class="status-badge status-off">INACTIVE</span></td>
                            </tr>
                            <tr>
                                <td>09:15 AM</td>
                                <td>Turned ON</td>
                                <td>Air Conditioner</td>
                                <td><span class="status-badge status-on">ACTIVE</span></td>
                            </tr>
                            <tr>
                                <td>08:00 AM</td>
                                <td>Turned ON</td>
                                <td>Smart TV</td>
                                <td><span class="status-badge status-on">ACTIVE</span></td>
                            </tr>
                            <tr>
                                <td>07:30 AM</td>
                                <td>Turned ON</td>
                                <td>Computer</td>
                                <td><span class="status-badge status-on">ACTIVE</span></td>
                            </tr>
                            <tr>
                                <td>11:30 PM</td>
                                <td>Turned OFF</td>
                                <td>Water Heater</td>
                                <td><span class="status-badge status-off">INACTIVE</span></td>
                            </tr>
                            <tr>
                                <td>10:00 PM</td>
                                <td>Turned OFF</td>
                                <td>Living Room Light</td>
                                <td><span class="status-badge status-off">INACTIVE</span></td>
                            </tr>
                            <tr>
                                <td>09:45 PM</td>
                                <td>Auto Mode Enabled</td>
                                <td>System</td>
                                <td><span class="status-badge status-on">ACTIVE</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Settings Section -->
            <div class="content-section <?php echo $section === 'settings' ? 'active' : ''; ?>" id="settings">
                <div class="settings-section">
                    <h3><i class="fas fa-user-cog"></i> Account Settings</h3>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" value="<?php echo $username; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" placeholder="user@example.com">
                    </div>
                    <div class="form-group">
                        <label>Change Password</label>
                        <input type="password" placeholder="Enter new password">
                    </div>
                    <button class="btn">Save Changes</button>
                </div>

                <div class="settings-section">
                    <h3><i class="fas fa-cog"></i> System Preferences</h3>
                    <div class="form-group">
                        <label>Energy Rate (per kWh)</label>
                        <input type="number" value="1.00" step="0.01" placeholder="$1.00">
                    </div>
                    <div class="form-group">
                        <label>Currency</label>
                        <select>
                            <option>USD ($)</option>
                            <option>EUR (€)</option>
                            <option>GBP (£)</option>
                            <option>PHP (₱)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Time Zone</label>
                        <select>
                            <option>UTC</option>
                            <option>EST</option>
                            <option>PST</option>
                            <option>GMT+8</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Auto Mode</label>
                        <select>
                            <option>Enabled</option>
                            <option>Disabled</option>
                        </select>
                    </div>
                    <button class="btn">Save Preferences</button>
                </div>

                <div class="settings-section">
                    <h3><i class="fas fa-bell"></i> Notifications</h3>
                    <div class="form-group">
                        <label>Email Notifications</label>
                        <select>
                            <option>Enabled</option>
                            <option>Disabled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>High Usage Alert Threshold (kW)</label>
                        <input type="number" value="3.0" step="0.1" placeholder="3.0">
                    </div>
                    <div class="form-group">
                        <label>Daily Report</label>
                        <select>
                            <option>Enabled</option>
                            <option>Disabled</option>
                        </select>
                    </div>
                    <button class="btn">Save Notifications</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simulate real-time data updates
        function updateDashboard() {
            // PIR value (motion detection)
            const pirValue = Math.floor(Math.random() * 50) + 200;
            document.getElementById('pir-value').textContent = pirValue;

            // LDR value (light sensor)
            const ldrValue = Math.floor(Math.random() * 200) + 600;
            document.getElementById('ldr-value').textContent = ldrValue;

            // Current value
            const current = (Math.random() * 2 + 11).toFixed(1);
            document.getElementById('current-value').textContent = current + ' A';

            // Wattage
            const wattage = (parseFloat(current) * 220 / 1000).toFixed(2);
            document.getElementById('watt-value').textContent = wattage + ' kW';

            // Daily cost
            const dailyCost = (parseFloat(wattage) * 24 * 1.0).toFixed(2);
            document.getElementById('cost-day').textContent = '$' + dailyCost;

            // Monthly cost
            const monthlyCost = Math.floor(parseFloat(dailyCost) * 30);
            document.getElementById('cost-month').textContent = '$' + monthlyCost;
        }

        // Update every 3 seconds
        setInterval(updateDashboard, 3000);
        updateDashboard();

        // Toggle switch functionality
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const card = this.closest('.appliance-card');
                const statusBadge = card.querySelector('.status-badge');
                const powerValue = card.querySelector('.power-value');
                const autoToggle = card.querySelector('.auto-mode-toggle input');

                // Don't allow manual toggle if auto mode is on
                if (autoToggle && autoToggle.checked) {
                    this.checked = !this.checked; // Revert the change
                    return;
                }

                if (this.checked) {
                    statusBadge.textContent = 'ON';
                    statusBadge.className = 'status-badge status-on';
                    // Simulate power consumption
                    const powers = {
                        'Living Room Light': '120W',
                        'Ceiling Fan': '75W',
                        'Smart TV': '150W',
                        'Air Conditioner': '2000W',
                        'Water Heater': '3000W',
                        'Computer': '180W'
                    };
                    const applianceName = card.querySelector('.appliance-name').textContent;
                    powerValue.textContent = powers[applianceName] || '0W';
                } else {
                    statusBadge.textContent = 'OFF';
                    statusBadge.className = 'status-badge status-off';
                    powerValue.textContent = '0W';
                }
            });
        });

        // Auto mode functionality for Light
        const lightAutoToggle = document.getElementById('light-auto-toggle');
        const lightToggle = document.getElementById('light-toggle');
        const lightAutoLabel = document.getElementById('light-auto-label');
        const lightCard = lightToggle.closest('.appliance-card');

        if (lightAutoToggle) {
            lightAutoToggle.addEventListener('change', function() {
                if (this.checked) {
                    lightAutoLabel.textContent = 'Auto Mode ON';
                    lightAutoLabel.parentElement.classList.add('auto-mode-active');
                    lightToggle.disabled = true;

                    // Simulate auto mode behavior based on LDR (light sensor)
                    simulateLightAutoMode();
                } else {
                    lightAutoLabel.textContent = 'Auto Mode';
                    lightAutoLabel.parentElement.classList.remove('auto-mode-active');
                    lightToggle.disabled = false;
                    clearInterval(window.lightAutoInterval);
                }
            });
        }

        function simulateLightAutoMode() {
            if (!lightAutoToggle.checked) return;

            window.lightAutoInterval = setInterval(() => {
                if (!lightAutoToggle.checked) {
                    clearInterval(window.lightAutoInterval);
                    return;
                }

                // Simulate LDR reading (0-1000, lower = darker)
                const ldrReading = parseInt(document.getElementById('ldr-value').textContent) || 600;
                const lightStatusBadge = lightCard.querySelector('.status-badge');
                const lightPowerValue = lightCard.querySelector('.power-value');

                if (ldrReading < 500) {
                    // Dark - turn on light
                    lightToggle.checked = true;
                    lightStatusBadge.textContent = 'ON';
                    lightStatusBadge.className = 'status-badge status-on';
                    lightPowerValue.textContent = '120W';
                } else {
                    // Bright - turn off light
                    lightToggle.checked = false;
                    lightStatusBadge.textContent = 'OFF';
                    lightStatusBadge.className = 'status-badge status-off';
                    lightPowerValue.textContent = '0W';
                }
            }, 5000); // Check every 5 seconds
        }

        // Auto mode functionality for Fan
        const fanAutoToggle = document.getElementById('fan-auto-toggle');
        const fanToggle = document.getElementById('fan-toggle');
        const fanAutoLabel = document.getElementById('fan-auto-label');
        const fanCard = fanToggle.closest('.appliance-card');

        if (fanAutoToggle) {
            fanAutoToggle.addEventListener('change', function() {
                if (this.checked) {
                    fanAutoLabel.textContent = 'Auto Mode ON';
                    fanAutoLabel.parentElement.classList.add('auto-mode-active');
                    fanToggle.disabled = true;

                    // Simulate auto mode behavior based on temperature/PIR
                    simulateFanAutoMode();
                } else {
                    fanAutoLabel.textContent = 'Auto Mode';
                    fanAutoLabel.parentElement.classList.remove('auto-mode-active');
                    fanToggle.disabled = false;
                    clearInterval(window.fanAutoInterval);
                }
            });

            // Initialize fan auto mode if it's already checked
            if (fanAutoToggle.checked) {
                fanAutoLabel.textContent = 'Auto Mode ON';
                fanAutoLabel.parentElement.classList.add('auto-mode-active');
                fanToggle.disabled = true;
                simulateFanAutoMode();
            }
        }

        function simulateFanAutoMode() {
            if (!fanAutoToggle.checked) return;

            window.fanAutoInterval = setInterval(() => {
                if (!fanAutoToggle.checked) {
                    clearInterval(window.fanAutoInterval);
                    return;
                }

                // Simulate motion detection (PIR) and temperature
                const pirReading = parseInt(document.getElementById('pir-value').textContent) || 245;
                const fanStatusBadge = fanCard.querySelector('.status-badge');
                const fanPowerValue = fanCard.querySelector('.power-value');

                // Turn on fan if motion detected (PIR > 200)
                if (pirReading > 200) {
                    fanToggle.checked = true;
                    fanStatusBadge.textContent = 'ON';
                    fanStatusBadge.className = 'status-badge status-on';
                    fanPowerValue.textContent = '75W';
                } else {
                    fanToggle.checked = false;
                    fanStatusBadge.textContent = 'OFF';
                    fanStatusBadge.className = 'status-badge status-off';
                    fanPowerValue.textContent = '0W';
                }
            }, 5000); // Check every 5 seconds
        }
    </script>
</body>
</html>
