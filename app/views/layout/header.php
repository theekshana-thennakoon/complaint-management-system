<?php
/**
 * Mahajanadinaya
 * MVC View: Public Header Component
 */
$route_param = $_GET['url'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    
    <!-- JS Dependencies (jQuery & SweetAlert) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const BASE_URL = '<?php echo URLROOT; ?>';
    </script>
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="<?php echo URLROOT; ?>" class="logo-section">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <h1><?php echo SITENAME; ?></h1>
                    <span>Complaint Management</span>
                </div>
            </a>
            
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Toggle Navigation" style="display: none; background: transparent; border: none; font-size: 1.4rem; color: var(--primary-color); cursor: pointer; padding: 6px 10px; border-radius: 8px; transition: background 0.2s;">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="nav-wrapper" id="nav-wrapper" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <nav class="main-nav">
                    <ul>
                        <li><a href="<?php echo URLROOT; ?>" class="nav-link <?php echo empty($route_param) ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="<?php echo URLROOT; ?>/publiccomplaint/status" class="nav-link <?php echo strpos($route_param, 'publiccomplaint/status') !== false ? 'active' : ''; ?>">Check Status</a></li>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['user_role_id'] != 5 && $_SESSION['user_role_id'] != 4 && $_SESSION['user_role_id'] != 3 && $_SESSION['user_role_id'] != 2 && $_SESSION['user_role_id'] != 1): ?>
                                <li><a href="<?php echo URLROOT; ?>/dashboard" class="nav-link <?php echo strpos($route_param, 'dashboard') !== false ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <?php endif; ?>
                            <?php if ($_SESSION['user_role_id'] == 1): ?>
                                <li><a href="<?php echo URLROOT; ?>/admin" class="nav-link <?php echo strpos($route_param, 'admin') !== false ? 'active' : ''; ?>"><i class="fas fa-users-cog"></i> Admin</a></li>
                            <?php elseif ($_SESSION['user_role_id'] == 5): ?>
                                <li><a href="<?php echo URLROOT; ?>/cc" class="nav-link <?php echo strpos($route_param, 'cc') !== false ? 'active' : ''; ?>"><i class="fas fa-inbox"></i> CC Dashboard</a></li>
                            <?php elseif ($_SESSION['user_role_id'] == 4): ?>
                                <li><a href="<?php echo URLROOT; ?>/ao" class="nav-link <?php echo strpos($route_param, 'ao') !== false ? 'active' : ''; ?>"><i class="fas fa-user-tie"></i> AO Dashboard</a></li>
                            <?php elseif ($_SESSION['user_role_id'] == 3): ?>
                                <li><a href="<?php echo URLROOT; ?>/gs" class="nav-link <?php echo strpos($route_param, 'gs') !== false ? 'active' : ''; ?>"><i class="fas fa-landmark"></i> GS Dashboard</a></li>
                            <?php elseif ($_SESSION['user_role_id'] == 2): ?>
                                <li><a href="<?php echo URLROOT; ?>/governor" class="nav-link <?php echo strpos($route_param, 'governor') !== false ? 'active' : ''; ?>"><i class="fas fa-crown"></i> Governor</a></li>
                            <?php endif; ?>

                            <li>
                                <div style="display:flex; align-items:center; gap:10px; padding: 5px 10px; background: var(--primary-50); border-radius: 50px; border: 1px solid var(--panel-border);">
                                    <div style="width:30px; height:30px; border-radius:50%; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display:flex; align-items:center; justify-content:center; color:white; font-size:0.75rem; font-weight:700; flex-shrink:0;">
                                        <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                    <span style="font-size:0.82rem; font-weight:600; color: var(--text-primary); max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></span>
                                    <a href="<?php echo URLROOT; ?>/auth/logout" style="color: var(--danger); font-size:0.8rem; font-weight:600; white-space:nowrap; text-decoration:none;" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li><a href="<?php echo URLROOT; ?>/auth/login" class="nav-link <?php echo strpos($route_param, 'auth/login') !== false ? 'active' : ''; ?>">Login</a></li>
                            <li><a href="<?php echo URLROOT; ?>/auth/register" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

