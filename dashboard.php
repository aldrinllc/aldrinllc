<?php
session_start();
if (!isset($_SESSION['auth']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$current_section = isset($_GET['section']) ? htmlspecialchars($_GET['section'], ENT_QUOTES, 'UTF-8') : 'dashboard';
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            background-size: 400% 400%;
            background-attachment: fixed;
            color: #ffffff;
            min-height: 100vh;
            animation: gradientShift 15s ease infinite;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-right: 1px solid rgba(76, 175, 80, 0.2);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 30px 20px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(76, 175, 80, 0.3);
            border-radius: 3px;
        }

        .logo-section {
            margin-bottom: 40px;
            text-align: center;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(76, 175, 80, 0.2);
        }

        .logo-section h1 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-shadow: 0 2px 10px rgba(76, 175, 80, 0.5);
            margin-bottom: 5px;
            background: linear-gradient(135deg, #4caf50, #00bcd4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-section p {
            font-size: 0.85rem;
            opacity: 0.8;
            font-weight: 300;
            color: #00bcd4;
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
            color: #00bcd4;
        }

        .nav-menu a:hover {
            background: rgba(76, 175, 80, 0.15);
            color: #ffffff;
            transform: translateX(5px);
        }

        .nav-menu a.active {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.25), rgba(0, 188, 212, 0.15));
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            border-left: 3px solid #4caf50;
        }

        .logout-btn {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(76, 175, 80, 0.2);
        }

        .logout-btn a {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid rgba(244, 67, 54, 0.4);
        }

        .logout-btn a i {
            color: #f44336;
        }

        .logout-btn a:hover {
            background: rgba(244, 67, 54, 0.3);
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
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .topbar-left h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .topbar-left p {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 300;
            color: #00bcd4;
        }

        .profile-box {
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(76, 175, 80, 0.1);
            padding: 10px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .profile-box:hover {
            background: rgba(76, 175, 80, 0.15);
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4caf50 0%, #00bcd4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        }

        .profile-info strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 3px;
        }

        .profile-info small {
            font-size: 0.85rem;
            opacity: 0.7;
            color: #00bcd4;
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
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(76, 175, 80, 0.2);
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
            background: linear-gradient(90deg, #4caf50, #00bcd4, #2196f3);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(76, 175, 80, 0.3);
            background: rgba(26, 26, 46, 0.9);
            border-color: rgba(76, 175, 80, 0.4);
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
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.2), rgba(0, 188, 212, 0.2));
            color: #4caf50;
        }

        .card-title {
            font-size: 0.95rem;
            opacity: 0.8;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #00bcd4;
        }

        .card-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 15px 0;
            background: linear-gradient(135deg, #4caf50, #00bcd4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-footer {
            font-size: 0.85rem;
            opacity: 0.7;
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
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(25px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(76, 175, 80, 0.2);
            transition: all 0.3s ease;
        }

        .appliance-card:hover {
            transform: translateY(-5px);
            background: rgba(26, 26, 46, 0.9);
            border-color: rgba(76, 175, 80, 0.4);
        }

        .appliance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .appliance-icon {
            font-size: 2.5rem;
            color: #00bcd4;
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
            background-color: rgba(158, 158, 158, 0.3);
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
        }

        input:checked + .toggle-slider {
            background: linear-gradient(135deg, #4caf50, #00bcd4);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(30px);
        }

        .appliance-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .appliance-status {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        .appliance-power {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(76, 175, 80, 0.2);
            font-size: 0.9rem;
        }

        .power-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 5px;
            color: #ffa726;
        }

        .auto-mode-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(76, 175, 80, 0.2);
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
            background-color: rgba(158, 158, 158, 0.3);
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
        }

        .auto-mode-toggle input:checked + .auto-mode-slider {
            background: linear-gradient(135deg, #4caf50, #00bcd4);
        }

        .auto-mode-toggle input:checked + .auto-mode-slider:before {
            transform: translateX(24px);
        }

        .auto-mode-active {
            color: #4caf50;
        }

        /* Table Styles */
        .table-container {
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(25px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(76, 175, 80, 0.2);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            border-bottom: 2px solid rgba(76, 175, 80, 0.3);
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
            opacity: 0.9;
            color: #00bcd4;
        }

        tbody tr {
            border-bottom: 1px solid rgba(76, 175, 80, 0.1);
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(76, 175, 80, 0.1);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-on {
            background: rgba(76, 175, 80, 0.3);
            color: #4caf50;
        }

        .status-off {
            background: rgba(158, 158, 158, 0.3);
            color: #9e9e9e;
        }

        /* Chart Container */
        .chart-container {
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(25px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(76, 175, 80, 0.2);
            margin-bottom: 25px;
            min-height: 400px;
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            color: #00bcd4;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 320px;
            background: rgba(15, 52, 96, 0.3);
            border-radius: 15px;
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
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }

        /* Settings Form */
        .settings-section {
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(25px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(76, 175, 80, 0.2);
            margin-bottom: 25px;
        }

        .settings-section h3 {
            margin-bottom: 20px;
            font-size: 1.3rem;
            font-weight: 600;
            color: #00bcd4;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            color: #00bcd4;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(15, 52, 96, 0.3);
            border: 1px solid rgba(76, 175, 80, 0.3);
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            background: rgba(15, 52, 96, 0.5);
            border-color: #4caf50;
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.3);
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, #4caf50 0%, #00bcd4 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
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
                <li><a href="?section=dashboard" class="<?php echo $current_section === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a></li>
                <li><a href="?section=appliances" class="<?php echo $current_section === 'appliances' ? 'active' : ''; ?>">
                    <i class="fas fa-lightbulb"></i>
                    <span>Appliances</span>
                </a></li>
                <li><a href="?section=energy" class="<?php echo $current_section === 'energy' ? 'active' : ''; ?>">
                    <i class="fas fa-bolt"></i>
                    <span>Energy Monitor</span>
                </a></li>
                <li><a href="?section=summary" class="<?php echo $current_section === 'summary' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Summary</span>
                </a></li>
                <li><a href="?section=logs" class="<?php echo $current_section === 'logs' ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i>
                    <span>Activity Logs</span>
                </a></li>
                <li><a href="?section=settings" class="<?php echo $current_section === 'settings' ? 'active' : ''; ?>">
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
                        echo $titles[$current_section] ?? 'Dashboard';
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
            <div class="content-section <?php echo $current_section === 'dashboard' ? 'active' : ''; ?>" id="dashboard">
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
            <div class="content-section <?php echo $current_section === 'appliances' ? 'active' : ''; ?>" id="appliances">
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
            <div class="content-section <?php echo $current_section === 'energy' ? 'active' : ''; ?>" id="energy">
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
                                    <stop offset="0%" style="stop-color:#4caf50;stop-opacity:0.8" />
                                    <stop offset="50%" style="stop-color:#00bcd4;stop-opacity:0.5" />
                                    <stop offset="100%" style="stop-color:#2196f3;stop-opacity:0.2" />
                                </linearGradient>
                                <filter id="glow">
                                    <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                                    <feMerge>
                                        <feMergeNode in="coloredBlur"/>
                                        <feMergeNode in="SourceGraphic"/>
                                    </feMerge>
                                </filter>
                            </defs>
                            
                            <!-- Grid lines -->
                            <g stroke="rgba(76, 175, 80, 0.2)" stroke-width="1" stroke-dasharray="5,5">
                                <line x1="80" y1="40" x2="80" y2="240"/>
                                <line x1="80" y1="240" x2="850" y2="240"/>
                                <line x1="80" y1="200" x2="850" y2="200"/>
                                <line x1="80" y1="160" x2="850" y2="160"/>
                                <line x1="80" y1="120" x2="850" y2="120"/>
                                <line x1="80" y1="80" x2="850" y2="80"/>
                                <line x1="80" y1="40" x2="850" y2="40"/>
                            </g>
                            
                            <!-- Y-axis labels -->
                            <text x="70" y="245" fill="#00bcd4" font-size="14" font-weight="600" text-anchor="end">0</text>
                            <text x="70" y="205" fill="#00bcd4" font-size="14" font-weight="600" text-anchor="end">1</text>
                            <text x="70" y="165" fill="#00bcd4" font-size="14" font-weight="600" text-anchor="end">2</text>
                            <text x="70" y="125" fill="#00bcd4" font-size="14" font-weight="600" text-anchor="end">3</text>
                            <text x="70" y="85" fill="#00bcd4" font-size="14" font-weight="600" text-anchor="end">4</text>
                            <text x="70" y="45" fill="#00bcd4" font-size="14" font-weight="600" text-anchor="end">5 kW</text>
                            
                            <!-- Area under curve -->
                            <path id="energy-area" d="M80,200 L190,180 L300,150 L410,120 L520,140 L630,110 L740,130 L850,115 L850,240 L80,240 Z" 
                                  fill="url(#energyGradient)" opacity="0.8"/>
                            
                            <!-- Energy line path with glow -->
                            <path id="energy-line" d="M80,200 L190,180 L300,150 L410,120 L520,140 L630,110 L740,130 L850,115" 
                                  fill="none" stroke="#4caf50" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" filter="url(#glow)"/>
                            
                            <!-- Data points with glow -->
                            <circle cx="80" cy="200" r="6" fill="#4caf50" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            <circle cx="190" cy="180" r="6" fill="#4caf50" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            <circle cx="300" cy="150" r="6" fill="#00bcd4" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            <circle cx="410" cy="120" r="6" fill="#00bcd4" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            <circle cx="520" cy="140" r="6" fill="#00bcd4" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            <circle cx="630" cy="110" r="6" fill="#2196f3" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            <circle cx="740" cy="130" r="6" fill="#2196f3" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            <circle cx="850" cy="115" r="6" fill="#2196f3" stroke="#ffffff" stroke-width="2" filter="url(#glow)"/>
                            
                            <!-- X-axis labels -->
                            <text x="80" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">00:00</text>
                            <text x="190" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">04:00</text>
                            <text x="300" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">08:00</text>
                            <text x="410" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">12:00</text>
                            <text x="520" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">16:00</text>
                            <text x="630" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">18:00</text>
                            <text x="740" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">20:00</text>
                            <text x="850" y="265" fill="#00bcd4" font-size="13" font-weight="500" text-anchor="middle">24:00</text>
                        </svg>
                    </div>
                </div>

                <div class="chart-container">
                    <h3 class="chart-title"><i class="fas fa-chart-pie"></i> Usage by Appliance</h3>
                    <div class="chart-wrapper">
                        <svg class="chart-svg" viewBox="0 0 500 300" id="pie-chart">
                            <defs>
                                <filter id="shadow">
                                    <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.5"/>
                                </filter>
                            </defs>
                            
                            <!-- Pie chart segments with improved visibility -->
                            <!-- Air Conditioner (73% - largest slice) - RED for high consumption -->
                            <path d="M 250 150 L 250 50 A 100 100 0 1 1 140 90 Z" fill="#f44336" stroke="#ffffff" stroke-width="3" filter="url(#shadow)"/>
                            
                            <!-- Computer (7%) - ORANGE -->
                            <path d="M 250 150 L 140 90 A 100 100 0 0 1 130 120 Z" fill="#ffa726" stroke="#ffffff" stroke-width="3" filter="url(#shadow)"/>
                            
                            <!-- Smart TV (5%) - YELLOW -->
                            <path d="M 250 150 L 130 120 A 100 100 0 0 1 135 145 Z" fill="#ffeb3b" stroke="#ffffff" stroke-width="3" filter="url(#shadow)"/>
                            
                            <!-- Light (4%) - GREEN for efficiency -->
                            <path d="M 250 150 L 135 145 A 100 100 0 0 1 150 165 Z" fill="#4caf50" stroke="#ffffff" stroke-width="3" filter="url(#shadow)"/>
                            
                            <!-- Fan (3%) - TEAL -->
                            <path d="M 250 150 L 150 165 A 100 100 0 0 1 250 50 Z" fill="#00bcd4" stroke="#ffffff" stroke-width="3" filter="url(#shadow)"/>
                            
                            <!-- Center circle for donut effect -->
                            <circle cx="250" cy="150" r="55" fill="rgba(15, 52, 96, 0.9)" stroke="rgba(76, 175, 80, 0.3)" stroke-width="2"/>
                            <text x="250" y="140" fill="#00bcd4" font-size="18" font-weight="700" text-anchor="middle">Total</text>
                            <text x="250" y="165" fill="#4caf50" font-size="22" font-weight="700" text-anchor="middle">2.75 kW</text>
                        </svg>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f44336; box-shadow: 0 0 10px rgba(244, 67, 54, 0.5);"></div>
                            <span>Air Conditioner (73%) - High</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #ffa726; box-shadow: 0 0 10px rgba(255, 167, 38, 0.5);"></div>
                            <span>Computer (7%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #ffeb3b; box-shadow: 0 0 10px rgba(255, 235, 59, 0.5);"></div>
                            <span>Smart TV (5%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #4caf50; box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);"></div>
                            <span>Light (4%) - Efficient</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #00bcd4; box-shadow: 0 0 10px rgba(0, 188, 212, 0.5);"></div>
                            <span>Fan (3%)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="content-section <?php echo $current_section === 'summary' ? 'active' : ''; ?>" id="summary">
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
                    <h3 style="margin-bottom: 20px; font-size: 1.3rem; font-weight: 600; color: #00bcd4;">Weekly Summary</h3>
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
            <div class="content-section <?php echo $current_section === 'logs' ? 'active' : ''; ?>" id="logs">
                <div class="table-container">
                    <h3 style="margin-bottom: 20px; font-size: 1.3rem; font-weight: 600; color: #00bcd4;">Recent Activity</h3>
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
            <div class="content-section <?php echo $current_section === 'settings' ? 'active' : ''; ?>" id="settings">
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
