  <!DOCTYPE html>
  <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>@yield('title', 'CuniApp Élevage')</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
          rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      @stack('styles')
      <style>
          :root {
              --primary: #2563EB;
              --primary-light: #3B82F6;
              --primary-dark: #1D4ED8;
              --primary-subtle: #EFF6FF;
              --accent-cyan: #06B6D4;
              --accent-purple: #8B5CF6;
              --accent-pink: #EC4899;
              --accent-green: #10B981;
              --accent-orange: #F59E0B;
              --accent-red: #EF4444;
              --white: #FFFFFF;
              --gray-50: #F9FAFB;
              --gray-100: #F3F4F6;
              --gray-200: #E5E7EB;
              --gray-300: #D1D5DB;
              --gray-400: #9CA3AF;
              --gray-500: #6B7280;
              --gray-600: #4B5563;
              --gray-700: #374151;
              --gray-800: #1F2937;
              --surface: #FFFFFF;
              --surface-alt: #F9FAFB;
              --surface-border: #E5E7EB;
              --text-primary: #1F2937;
              --text-secondary: #6B7280;
              --text-tertiary: #9CA3AF;
              --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
              --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
              --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
              --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
              --radius: 8px;
              --radius-md: 12px;
              --radius-lg: 16px;
              --radius-xl: 20px;
          }

          .theme-dark {
              --surface: #0A0F1D;
              --surface-alt: #0F172A;
              --surface-elevated: #151E30;
              --surface-overlay: #1E293B;
              --surface-border: #25324A;
              --text-primary: #E6E9F0;
              --text-secondary: #A3B3C6;
              --text-tertiary: #6B7D95;
              --primary: #4DA6FF;
              --primary-subtle: rgba(77, 166, 255, 0.12);
              --accent-green: #34D399;
              --accent-orange: #FB923C;
              --accent-red: #F87171;
              --gray-50: #080C15;
              --gray-100: #0F172A;
              --gray-200: #1A2335;
              background-color: var(--surface);
              color: var(--text-primary);
          }

          * {
              margin: 0;
              padding: 0;
              box-sizing: border-box;
          }

          html,
          body {
              min-width: 100%;
              width: 100%;
          }

          body {
              font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
              background: var(--gray-50);
              color: var(--text-primary);
              line-height: 1.6;
              min-height: 100vh;
              display: flex;
              flex-direction: column;
              min-width: 320px;
          }

          .cuni-header {
              background: var(--surface);
              border-bottom: 1px solid var(--surface-border);
              padding: 0;
              position: sticky;
              top: 0;
              z-index: 100;
              min-width: 100%;
              box-shadow: var(--shadow-sm);
          }

          .header-wrapper {
              display: flex;
              align-items: center;
              justify-content: space-between;
              height: 72px;
              padding: 0 2.5rem;
              max-width: 1600px;
              margin: 0 auto;
              width: 100%;
              gap: 20px;
          }

          .brand-identity {
              display: flex;
              align-items: center;
              gap: 14px;
              flex-shrink: 0;
          }

          .cuniapp-logo {
              width: 44px;
              height: 44px;
              display: flex;
              align-items: center;
              justify-content: center;
              background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
              border-radius: var(--radius-md);
              transition: transform 0.2s ease;
              flex-shrink: 0;
          }

          .cuniapp-logo:hover {
              transform: scale(1.05);
          }

          .cuniapp-logo svg {
              width: 28px;
              height: 28px;
          }

          .brand-info {
              display: flex;
              flex-direction: column;
              min-width: 0;
          }

          .brand-title {
              font-size: 1.15rem;
              margin: 0;
              line-height: 1.1;
              color: var(--gray-800);
              white-space: nowrap;
              font-weight: 700;
          }

          .theme-dark .brand-title {
              color: #FFFFFF !important;
          }

          .brand-tagline {
              display: block !important;
              font-size: 0.72rem;
              color: var(--text-secondary);
              opacity: 0.85;
              margin: 0;
              padding: 4px 0 0 0;
          }

          .nav-main-links {
              display: flex;
              align-items: center;
              gap: 6px;
              flex: 1;
              justify-content: center;
              overflow: visible;
              -webkit-overflow-scrolling: touch;
              scrollbar-width: none;
              -ms-overflow-style: none;
          }

          .nav-main-links::-webkit-scrollbar {
              display: none;
          }

          .nav-link {
              display: flex;
              align-items: center;
              gap: 8px;
              padding: 9px 15px;
              text-decoration: none;
              white-space: nowrap;
              transition: all 0.2s ease;
              color: var(--text-secondary);
              border-radius: var(--radius);
              font-size: 14px;
              font-weight: 500;
              flex-shrink: 0;
              border: none;
              background: none;
              cursor: pointer;
          }

          .nav-link:hover {
              background: var(--gray-50);
              color: var(--primary);
          }

          .nav-link.active {
              background: var(--primary-subtle);
              color: var(--primary);
          }

          .nav-link i {
              font-size: 16px;
          }

          .nav-user-side {
              display: flex;
              align-items: center;
              gap: 14px;
              flex-shrink: 0;
          }

          .notification-trigger {
              position: relative;
              display: inline-flex;
              align-items: center;
              justify-content: center;
              width: 42px;
              height: 42px;
              border-radius: var(--radius);
              background: var(--gray-50);
              border: 1px solid var(--surface-border);
              cursor: pointer;
              transition: all 0.2s;
              flex-shrink: 0;
              text-decoration: none;
          }

          .notification-trigger:hover {
              background: var(--gray-100);
              border-color: var(--gray-300);
          }

          .notification-trigger i {
              font-size: 18px;
              color: var(--text-secondary);
          }

          .notification-badge {
              position: absolute;
              top: -5px;
              right: -5px;
              background: var(--accent-red);
              color: white;
              font-size: 10px;
              font-weight: 700;
              min-width: 19px;
              height: 19px;
              border-radius: 10px;
              display: flex;
              align-items: center;
              justify-content: center;
              border: 2px solid var(--surface);
          }

          .user-profile-dropdown {
              position: relative;
              display: inline-block;
          }

          .user-trigger {
              display: flex;
              align-items: center;
              gap: 10px;
              padding: 7px 13px;
              border-radius: var(--radius-md);
              cursor: pointer;
              transition: all 0.2s;
              background: var(--gray-50);
              border: 1px solid var(--surface-border);
              white-space: nowrap;
          }

          .user-trigger:hover {
              background: var(--gray-100);
          }

          .user-avatar {
              width: 32px;
              height: 32px;
              background: var(--primary);
              color: white;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              font-weight: 600;
              font-size: 14px;
              flex-shrink: 0;
          }

          /* ===== DROPDOWN CONTAINER - FIX FOR MORE BUTTON ===== */
          .dropdown-container {
              position: relative;
              display: inline-block;
          }

          .dropdown-menu-custom {
              position: absolute;
              top: calc(100% + 8px);
              right: 0;
              width: 280px;
              background: var(--surface);
              border: 1px solid var(--surface-border);
              border-radius: var(--radius-lg);
              box-shadow: var(--shadow-lg);
              display: none;
              z-index: 1000;
              overflow: hidden;
              animation: slideIn 0.2s ease-out;
              padding: 8px;
          }

          /* More Dropdown Specific Positioning */
          #moreDropdown {
              left: 0;
              right: auto;
              width: 240px;
          }

          .dropdown-menu-custom.show {
              display: block !important;
              visibility: visible !important;
              opacity: 1 !important;
          }

          @keyframes slideIn {
              from {
                  opacity: 0;
                  transform: translateY(-10px);
              }

              to {
                  opacity: 1;
                  transform: translateY(0);
              }
          }

          .dropdown-header {
              padding: 16px 20px;
              border-bottom: 1px solid var(--surface-border);
              background: var(--surface-alt);
              margin-bottom: 4px;
          }

          .dropdown-header span {
              font-size: 14px;
              font-weight: 700;
              margin-bottom: 2px;
          }

          .dropdown-header small {
              font-size: 12px;
              opacity: 0.7;
              display: block;
          }

          .dropdown-item-custom {
              display: flex;
              align-items: center;
              gap: 14px;
              padding: 12px 16px;
              margin: 4px 0;
              border-radius: var(--radius);
              color: var(--text-primary);
              text-decoration: none;
              font-size: 14px;
              transition: all 0.2s;
              width: 100%;
              border: none;
              background: none;
              text-align: left;
              cursor: pointer;
          }

          .dropdown-item-custom:hover {
              background: var(--gray-50);
              color: var(--primary);
          }

          .dropdown-item-custom.danger:hover {
              background: rgba(239, 68, 68, 0.05);
              color: var(--accent-red);
          }

          .theme-switch-row {
              cursor: pointer;
          }

          .theme-status-badge {
              font-size: 0.68rem;
              font-weight: 700;
              padding: 3px 9px;
              border-radius: 12px;
              background: rgba(0, 0, 0, 0.08);
              color: var(--text-secondary);
              text-transform: capitalize;
          }

          .cuni-main {
              max-width: 1600px;
              margin: 0 auto;
              padding: 28px;
              /* min-height: calc(100vh - 220px); */
              flex: 1;
              width: 100%;
              min-width: 320px;
          }

          .mobile-menu-trigger {
              display: none;
              cursor: pointer;
              font-size: 24px;
              color: var(--text-secondary);
              padding: 8px;
              border-radius: var(--radius);
              transition: all 0.2s;
              flex-shrink: 0;
              background: none;
              border: none;
          }

          .mobile-menu-trigger:hover {
              background: var(--gray-100);
              color: var(--primary);
          }

          .mobile-nav-overlay {
              position: fixed;
              top: 72px;
              left: 0;
              right: 0;
              bottom: 0;
              background: var(--surface);
              z-index: 99;
              transform: translateX(-100%);
              transition: transform 0.3s ease;
              overflow-y: auto;
              padding: 20px;
          }

          .mobile-nav-overlay.active {
              transform: translateX(0);
          }

          .mobile-nav-links {
              display: flex;
              flex-direction: column;
              gap: 8px;
          }

          .mobile-nav-link {
              display: flex;
              align-items: center;
              gap: 12px;
              padding: 14px 16px;
              text-decoration: none;
              color: var(--text-primary);
              border-radius: var(--radius);
              font-size: 15px;
              font-weight: 500;
              transition: all 0.2s;
          }

          .mobile-nav-link:hover {
              background: var(--gray-50);
              color: var(--primary);
          }

          .mobile-nav-link.active {
              background: var(--primary-subtle);
              color: var(--primary);
          }

          .mobile-nav-link i {
              font-size: 18px;
              width: 24px;
              text-align: center;
          }

          .mobile-nav-divider {
              height: 1px;
              background: var(--surface-border);
              margin: 16px 0;
          }

          .cuni-card {
              background: var(--surface);
              border-radius: var(--radius-lg);
              border: 1px solid var(--surface-border);
              box-shadow: var(--shadow);
              margin-bottom: 24px;
              overflow: hidden;
          }

          .card-header-custom {
              display: flex;
              justify-content: space-between;
              align-items: center;
              padding: 20px 24px;
              border-bottom: 1px solid var(--surface-border);
              background: var(--surface-alt);
          }

          .card-title {
              font-size: 16px;
              font-weight: 600;
              color: var(--gray-800);
              display: flex;
              align-items: center;
              gap: 10px;
          }

          .theme-dark .card-title {
              color: var(--white);
          }

          .card-title i {
              color: var(--primary);
          }

          .card-body {
              padding: 24px;
          }

          .btn-cuni {
              display: inline-flex;
              align-items: center;
              justify-content: center;
              gap: 8px;
              padding: 10px 20px;
              font-size: 14px;
              font-weight: 500;
              border-radius: var(--radius);
              border: none;
              cursor: pointer;
              text-decoration: none;
              transition: all 0.2s ease;
              font-family: inherit;
          }

          .btn-cuni.primary {
              background: var(--primary);
              color: var(--white);
              box-shadow: var(--shadow-sm);
          }

          .btn-cuni.primary:hover {
              background: var(--primary-dark);
              transform: translateY(-1px);
              box-shadow: var(--shadow);
          }

          .btn-cuni.secondary {
              background: var(--white);
              color: var(--text-primary);
              border: 1px solid var(--surface-border);
          }

          .theme-dark .btn-cuni.secondary {
              background: var(--surface-elevated);
              border-color: var(--surface-border);
              color: var(--text-secondary);
          }

          .theme-dark .btn-cuni.secondary:hover {
              background: var(--gray-200);
              color: var(--primary);
              border-color: var(--primary);
          }

          .btn-cuni.danger {
              background: rgba(239, 68, 68, 0.1);
              color: var(--accent-red);
              border: 1px solid rgba(239, 68, 68, 0.2);
          }

          .btn-cuni.danger:hover {
              background: var(--accent-red);
              color: var(--white);
              border-color: var(--accent-red);
          }

          .btn-cuni.sm {
              padding: 8px 14px;
              font-size: 13px;
          }

          .form-group {
              margin-bottom: 20px;
          }

          .form-label {
              display: block;
              font-size: 14px;
              font-weight: 500;
              color: var(--gray-700);
              margin-bottom: 8px;
              transition: color 0.2s ease;
          }

          .theme-dark .form-label {
              color: var(--text-secondary);
          }

          .form-control,
          .form-select {
              width: 100%;
              padding: 12px 16px;
              font-size: 14px;
              font-family: inherit;
              color: var(--text-primary);
              background-color: var(--white);
              border: 1px solid var(--gray-300);
              border-radius: var(--radius);
              transition: all 0.2s ease;
              box-shadow: var(--shadow-sm);
          }

          .theme-dark .form-control,
          .theme-dark .form-select {
              background-color: var(--surface-alt);
              border-color: var(--surface-border);
              color: var(--text-primary);
              box-shadow: none;
          }

          .form-control:focus,
          .form-select:focus {
              outline: none;
              border-color: var(--primary);
              background-color: var(--white);
              box-shadow: 0 0 0 4px var(--primary-subtle);
          }

          .theme-dark .form-control:focus,
          .theme-dark .form-select:focus {
              background-color: var(--surface-elevated);
              border-color: var(--primary);
              box-shadow: 0 0 0 4px rgba(77, 166, 255, 0.15);
          }

          .form-control::placeholder {
              color: var(--text-tertiary);
          }

          .page-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              margin-bottom: 24px;
              flex-wrap: wrap;
              gap: 16px;
          }

          .page-title {
              font-size: 24px;
              font-weight: 700;
              color: var(--gray-800);
              display: flex;
              align-items: center;
              gap: 12px;
              letter-spacing: -0.02em;
          }

          .theme-dark .page-title {
              color: var(--white);
          }

          .page-title i {
              color: var(--primary);
              font-size: 26px;
          }

          .breadcrumb {
              display: flex;
              gap: 8px;
              font-size: 13px;
              color: var(--text-secondary);
              margin-top: 8px;
          }

          .breadcrumb a {
              color: var(--primary);
              text-decoration: none;
              transition: color 0.2s;
          }

          .breadcrumb a:hover {
              color: var(--primary-dark);
              text-decoration: underline;
          }

          .breadcrumb span {
              color: var(--text-tertiary);
          }

          .table-responsive {
              overflow-x: auto;
          }

          .table {
              width: 100%;
              border-collapse: collapse;
          }

          .table thead th {
              padding: 12px 16px;
              text-align: left;
              font-weight: 600;
              border-bottom: 2px solid var(--surface-border);
          }

          .table tbody td {
              padding: 12px 16px;
              border-bottom: 1px solid var(--surface-border);
              vertical-align: middle;
          }

          .table-hover tbody tr:hover {
              background: var(--surface-alt);
          }

          .badge {
              display: inline-block;
              padding: 4px 12px;
              font-size: 12px;
              font-weight: 600;
              border-radius: 20px;
              border: none;
          }

          .status-active {
              background: rgba(16, 185, 129, 0.1);
              color: var(--accent-green);
          }

          .status-inactive {
              background: rgba(107, 114, 128, 0.1);
              color: var(--gray-500);
          }

          .status-gestante {
              background: rgba(236, 72, 153, 0.1);
              color: var(--accent-pink);
          }

          .status-allaitante {
              background: rgba(139, 92, 246, 0.1);
              color: var(--accent-purple);
          }

          .status-vide {
              background: rgba(59, 130, 246, 0.1);
              color: var(--primary-light);
          }

          .status-malade {
              background: rgba(239, 68, 68, 0.1);
              color: var(--accent-red);
          }

          .action-buttons {
              display: flex;
              gap: 8px;
              justify-content: flex-end;
          }

          .table-empty-state {
              text-align: center;
              padding: 40px 20px;
              color: var(--text-tertiary);
          }

          .table-empty-state i {
              font-size: 48px;
              margin-bottom: 16px;
              opacity: 0.5;
          }

          .table-empty-state p {
              margin-bottom: 16px;
              font-size: 14px;
          }

          .alert-cuni {
              padding: 14px 18px;
              border-radius: var(--radius);
              margin-bottom: 24px;
              display: flex;
              align-items: flex-start;
              gap: 12px;
              font-size: 14px;
          }

          .alert-cuni i {
              font-size: 18px;
              flex-shrink: 0;
              margin-top: 2px;
          }

          .alert-cuni.success {
              background: rgba(16, 185, 129, 0.1);
              border: 1px solid rgba(16, 185, 129, 0.2);
              color: var(--accent-green);
          }

          .alert-cuni.error {
              background: rgba(239, 68, 68, 0.1);
              border: 1px solid rgba(239, 68, 68, 0.2);
              color: var(--accent-red);
          }

          .cuni-footer {
              background: var(--surface);
              border-top: 1px solid var(--surface-border);
              padding: 60px 0 30px 0;
              margin-top: auto;
          }

          .footer-container {
              max-width: 1600px;
              margin: 0 auto;
              padding: 0 2.5rem;
          }

          .footer-grid {
              display: grid;
              grid-template-columns: 2fr 1fr 1fr 1fr;
              gap: 40px;
              margin-bottom: 40px;
          }

          .footer-brand {
              display: flex;
              flex-direction: column;
              gap: 16px;
          }

          .footer-logo {
              display: flex;
              align-items: center;
              gap: 12px;
              margin-bottom: 8px;
          }

          .footer-logo-icon {
              width: 48px;
              height: 48px;
              background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
              border-radius: var(--radius-md);
              display: flex;
              align-items: center;
              justify-content: center;
          }

          .footer-logo-icon svg {
              width: 28px;
              height: 28px;
          }

          .footer-logo-text {
              font-size: 1.3rem;
              font-weight: 700;
              color: var(--text-primary);
          }

          .footer-logo-text span {
              color: var(--primary);
          }

          .footer-tagline {
              font-size: 0.9rem;
              color: var(--text-secondary);
              line-height: 1.6;
              max-width: 300px;
          }

          .footer-section h4 {
              font-size: 1rem;
              font-weight: 600;
              color: var(--text-primary);
              margin-bottom: 20px;
              display: flex;
              align-items: center;
              gap: 8px;
          }

          .footer-section h4 i {
              color: var(--primary);
          }

          .footer-links {
              list-style: none;
              display: flex;
              flex-direction: column;
              gap: 12px;
          }

          .footer-links li a {
              color: var(--text-secondary);
              text-decoration: none;
              font-size: 0.9rem;
              transition: all 0.2s ease;
              display: flex;
              align-items: center;
              gap: 8px;
          }

          .footer-links li a:hover {
              color: var(--primary);
              transform: translateX(4px);
          }

          .footer-links li a i {
              font-size: 12px;
              opacity: 0;
              transition: opacity 0.2s ease;
          }

          .footer-links li a:hover i {
              opacity: 1;
          }

          .footer-bottom {
              display: flex;
              justify-content: space-between;
              align-items: center;
              padding-top: 30px;
              border-top: 1px solid var(--surface-border);
              flex-wrap: wrap;
              gap: 20px;
          }

          .footer-copyright {
              font-size: 0.85rem;
              color: var(--text-tertiary);
          }

          .footer-copyright a {
              color: var(--primary);
              text-decoration: none;
          }

          .footer-legal {
              display: flex;
              gap: 24px;
          }

          .footer-legal a {
              font-size: 0.85rem;
              color: var(--text-tertiary);
              text-decoration: none;
              transition: color 0.2s ease;
          }

          .footer-legal a:hover {
              color: var(--primary);
          }

          .back-to-top {
              position: fixed;
              bottom: 30px;
              right: 30px;
              width: 45px;
              height: 45px;
              border-radius: 50%;
              background: var(--primary);
              color: white;
              border: none;
              box-shadow: var(--shadow-lg);
              cursor: pointer;
              display: none;
              align-items: center;
              justify-content: center;
              z-index: 1000;
              transition: all 0.3s ease;
          }

          .back-to-top:hover {
              transform: translateY(-5px);
              background: var(--primary-dark);
          }

          .back-to-top.show {
              display: flex;
              animation: fadeIn 0.3s ease;
          }

          @keyframes fadeIn {
              from {
                  opacity: 0;
                  transform: scale(0.8);
              }

              to {
                  opacity: 1;
                  transform: scale(1);
              }
          }

          .settings-grid {
              display: grid;
              grid-template-columns: repeat(2, 1fr);
              gap: 20px;
          }


          .form-section {
              background: var(--surface-alt);
              border-radius: var(--radius-lg);
              padding: 20px;
              border: 1px solid var(--surface-border);
          }

          .section-subtitle {
              font-size: 14px;
              font-weight: 600;
              color: var(--gray-700);
              margin-bottom: 16px;
              display: flex;
              align-items: center;
              gap: 8px;
          }

          .section-subtitle i {
              color: var(--primary);
          }


          /* Dark mode specific overrides */
          .theme-dark .cuni-header {
              background: #1e293b;
              border-bottom-color: #334155;
          }

          .theme-dark .nav-link {
              color: #94a3b8;
          }

          .theme-dark .nav-link:hover {
              background: var(--surface-elevated);
              color: var(--text-primary);
          }

          .theme-dark .nav-link.active {
              background: var(--primary-subtle);
              color: var(--primary);
          }

          .theme-dark .dropdown-menu-custom {
              background: var(--surface-overlay) !important;
              border-color: var(--surface-border);
              box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
          }

          .theme-dark .dropdown-item-custom {
              color: var(--text-primary);
          }

          .theme-dark .dropdown-header {
              background: var(--surface-elevated) !important;
              padding: 20px 24px;
          }

          .theme-dark .dropdown-item-custom:hover {
              background: var(--surface-overlay) !important;
              transform: translateX(4px);
              transition: all 0.2s ease;
          }

          .theme-dark .notification-trigger {
              background: var(--surface-elevated);
              border-color: var(--surface-border);
          }

          .theme-dark .notification-trigger:hover {
              background: var(--surface-overlay);
          }

          .theme-dark .user-trigger {
              background: var(--surface-elevated);
              border-color: var(--surface-border);
          }

          .theme-dark .user-trigger:hover {
              background: var(--surface-overlay);
          }

          .theme-dark .card-header-custom {
              background-color: var(--surface-alt) !important;
              border-bottom-color: var(--surface-border) !important;
          }

          .theme-dark .mobile-nav-overlay {
              background: var(--surface);
          }

          .theme-dark .mobile-nav-link {
              color: var(--text-primary);
          }

          .theme-dark .mobile-nav-link:hover {
              background: var(--surface-elevated);
              color: var(--primary);
          }

          .theme-dark .mobile-nav-link.active {
              background: var(--primary-subtle);
              color: var(--primary);
          }

          .d-md-none {
              display: none;
          }

         /* ===== UTILITAIRES RESPONSIVE ===== */
.d-none {
    display: none !important;
}

.d-md-none {
    display: none;
}

.d-md-flex {
    display: none;
}

@media (max-width: 1100px) {
    .d-md-none {
        display: block !important;
    }
    .d-md-flex {
        display: none !important;
    }
    
    /* Badge notification dans le menu mobile */
    .mobile-nav-link .notification-badge {
        position: static !important;
        margin-left: auto;
        font-size: 11px;
        min-width: 18px;
        height: 18px;
    }

    /* Avatar utilisateur dans le menu mobile */
    .mobile-nav-link .user-avatar {
        width: 28px;
        height: 28px;
        font-size: 13px;
        margin-right: 8px;
    }

    /* Espacement pour les sous-liens de profil */
    .mobile-nav-link[style*="padding-left: 52px"] {
        padding-left: 52px !important;
    }
}

@media (min-width: 1101px) {
    .d-md-none {
        display: none !important;
    }
    .d-md-flex {
        display: flex !important;
    }
}

/* ===== RESPONSIVE HEADER ===== */
@media (max-width: 1280px) {
    .header-wrapper {
        padding: 0 2rem;
    }

    .brand-tagline {
        display: none !important;
    }
}

@media (max-width: 1100px) {
    .header-wrapper {
        padding: 0 1.5rem;
        height: 68px;
    }

    .nav-main-links {
        display: none !important; /* ✅ Cache le menu desktop */
    }

    .mobile-menu-trigger {
        display: block !important; /* ✅ Affiche le burger */
    }

    .user-trigger span {
        display: none;
    }

    .cuni-main {
        padding: 20px;
    }
}

@media (max-width: 768px) {
    .header-wrapper {
        padding: 0 1.25rem;
        height: 64px;
    }

    .brand-info {
        min-width: 0;
    }

    .brand-title {
        font-size: 1.05rem;
    }

    .cuni-main {
        padding: 16px;
    }

    .footer-bottom {
        flex-direction: column;
        text-align: center;
    }

    .footer-legal {
        justify-content: center;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-title {
        font-size: 20px;
    }

    .card-header-custom {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .settings-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .header-wrapper {
        gap: 10px;
    }

    .cuniapp-logo {
        width: 40px;
        height: 40px;
    }

    .cuniapp-logo svg {
        width: 24px;
        height: 24px;
    }

    .brand-title {
        font-size: 1rem;
    }

    .notification-trigger {
        width: 38px;
        height: 38px;
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        font-size: 13px;
    }
}

/* ===== FOOTER RESPONSIVE FIX ===== */
@media (max-width: 1100px) {
    .footer-container {
        padding: 0 1.5rem;
    }

    .footer-brand {
        grid-column: 1 / -1;
        text-align: center;
    }

    .footer-logo {
        justify-content: center;
    }

    .footer-tagline {
        max-width: 400px;
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .footer-container {
        padding: 0 1.25rem;
    }

    .footer-grid {
        grid-template-columns: 1fr !important;
        gap: 24px;
    } 

    .footer-brand {
        grid-column: 1 / -1 !important;
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--surface-border);
    }

    .footer-logo {
        justify-content: center;
    }

    .footer-tagline {
        max-width: 400px;
        margin: 0 auto;
    }

    /* ✅ Les 3 sections en 3 colonnes */
    .footer-section {
        grid-column: span 1;
    }

    /* ✅ Container pour les 3 colonnes */
    .footer-grid > .footer-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .footer-section h4 {
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .footer-links li a {
        font-size: 0.8rem;
        padding: 3px 0;
    }

    .footer-contact-item {
        font-size: 0.8rem;
        margin-bottom: 6px;
    }

    .footer-bottom {
        flex-direction: column;
        text-align: center;
        gap: 16px;
        padding: 24px 0 0 0;
    }

    .footer-copyright {
        font-size: 0.8rem;
        order: 2;
    }

    .footer-legal {
        justify-content: center;
        flex-wrap: wrap;
        gap: 16px;
        order: 1;
    }

    .footer-legal a {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .footer-container {
        padding: 0 1rem;
    }

    .footer-logo-text {
        font-size: 1.1rem;
    }

    .footer-logo-icon {
        width: 40px;
        height: 40px;
    }

    /* ✅ 3 colonnes même sur très petit écran */
    .footer-grid > .footer-section {
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .footer-section h4 {
        font-size: 0.85rem;
    }

    .footer-links li a {
        font-size: 0.75rem;
    }

    .footer-contact-item {
        font-size: 0.75rem;
    }
}
      </style>
  </head>

  <body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">


      <header class="cuni-header">
          <div class="header-wrapper">
              <div class="brand-identity">
                  <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                      <svg viewBox="0 0 40 40" fill="none" style="width: 40px; height: 40px;">
                          <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                          <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                      </svg>
                  </a>
                  <div class="brand-info">
                      <a href="{{ route('dashboard') }}">
                          <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                      </a>
                      <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                  </div>
              </div>

              <nav class="nav-main-links" id="navMainLinks">
                  <a href="{{ route('dashboard') }}"
                      class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                      <i class="bi bi-speedometer2"></i>
                      <span>Tableau de bord</span>
                  </a>
                  <a href="{{ route('males.index') }}"
                      class="nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                      <i class="bi bi-arrow-up-right-square"></i>
                      <span>Mâles</span>
                  </a>
                  <a href="{{ route('femelles.index') }}"
                      class="nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                      <i class="bi bi-arrow-down-right-square"></i>
                      <span>Femelles</span>
                  </a>
                  <a href="{{ route('lapins.index') }}"
                      class="nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                      <i class="bi bi-collection"></i>
                      <span>Tous les Lapins</span>
                  </a>
                  <a href="{{ route('mises-bas.index') }}"
                      class="nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                      <i class="bi bi-egg"></i>
                      <span>Mises Bas</span>
                  </a>

                  <!-- Replace this section in the nav-main-links -->
                  <div class="dropdown-container">
                      <button class="nav-link" type="button" onclick="toggleMoreDropdown(event)" id="moreButton">
                          <i class="bi bi-three-dots"></i>
                          <span>Plus</span>
                          <i class="bi bi-chevron-down" style="font-size: 10px;"></i>
                      </button>
                      <div class="dropdown-menu-custom" id="moreDropdown">
                          <a href="{{ route('saillies.index') }}" class="dropdown-item-custom">
                              <i class="bi bi-heart"></i> Saillies
                          </a>
                          <a href="{{ route('naissances.index') }}" class="dropdown-item-custom">
                              <i class="bi bi-egg-fill"></i>
                              Naissances </a>
                          <a href="{{ route('sales.index') }}" class="dropdown-item-custom">
                              <i class="bi bi-cart"></i> Ventes
                          </a>
                          <a href="{{ route('activites.index') }}" class="dropdown-item-custom">
                              <i class="bi bi-clock-history"></i>
                              Activités </a>
                          <a href="{{ route('settings.index') }}" class="dropdown-item-custom">
                              <i class="bi bi-gear"></i> Paramètres
                          </a>
                      </div>
                  </div>
              </nav>

              <button class="mobile-menu-trigger d-md-none" onclick="toggleMobileNav()" aria-label="Menu">
                  <i class="bi bi-list"></i>
              </button>

              @auth
                  <div class="nav-user-side d-none d-md-flex">
                      <a href="{{ route('notifications.index') }}" class="notification-trigger">
                          <i class="bi bi-bell"></i>
                          @php
                              $unread = \App\Models\Notification::where('user_id', auth()->id())
                                  ->where('is_read', false)
                                  ->count();
                          @endphp
                          @if ($unread > 0)
                              <span class="notification-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                          @endif
                      </a>
                      <div class="user-profile-dropdown">
                          <div class="user-trigger" onclick="toggleUserDropdown(event)">
                              <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                              <span>{{ auth()->user()->name }}</span>
                              <i class="bi bi-chevron-down"></i>
                          </div>
                          <div class="dropdown-menu-custom" id="userDropdown">
                              <div class="dropdown-header">
                                  <span>{{ auth()->user()->name }}</span>
                                  <small>{{ auth()->user()->email }}</small>
                              </div>
                              <a href="{{ route('profile.edit') }}" class="dropdown-item-custom">
                                  <i class="bi bi-person"></i> Profil
                              </a>
                              <a href="{{ route('settings.index') }}" class="dropdown-item-custom">
                                  <i class="bi bi-gear"></i> Paramètres
                              </a>
                              <a href="{{ route('settings.index') }}#system-tab"
                                  class="dropdown-item-custom theme-switch-row" id="theme-selector">
                                  <div class="theme-info" style="display: flex; align-items: center; gap: 10px; flex: 1;">
                                      <i class="bi bi-palette" id="theme-icon-main" style="color: var(--primary);"></i>
                                      <span>Thème</span>
                                  </div>
                                  <div class="theme-status-badge" id="theme-badge">
                                      @php
                                          $themeValue = auth()->user()->theme ?? 'system';
                                          $themeLabel = match ($themeValue) {
                                              'system' => 'Système',
                                              'light' => 'Clair',
                                              'dark' => 'Sombre',
                                              default => 'Système',
                                          };
                                      @endphp
                                      <span id="theme-text">{{ $themeLabel }}</span>
                                  </div>
                              </a>
                              <hr style="border: none; border-top: 1px solid var(--surface-border); margin: 8px 0;">
                              <form method="POST" action="{{ route('logout') }}">
                                  @csrf
                                  <button type="submit" class="dropdown-item-custom logout-btn" style="width: 100%;">
                                      <i class="bi bi-box-arrow-right"></i> Déconnexion
                                  </button>
                              </form>
                          </div>
                      </div>
                  </div>
              @endauth
          </div>

          @auth
              <div class="mobile-nav-overlay" id="mobileNavOverlay">
                  <div class="mobile-nav-links">
                      <a href="{{ route('dashboard') }}"
                          class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                          <i class="bi bi-speedometer2"></i>
                          <span>Tableau de bord</span>
                      </a>
                      <a href="{{ route('males.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                          <i class="bi bi-arrow-up-right-square"></i>
                          <span>Mâles</span>
                      </a>
                      <a href="{{ route('femelles.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                          <i class="bi bi-arrow-down-right-square"></i>
                          <span>Femelles</span>
                      </a>
                      <a href="{{ route('lapins.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                          <i class="bi bi-collection"></i>
                          <span>Tous les Lapins</span>
                      </a>
                      <a href="{{ route('mises-bas.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                          <i class="bi bi-egg"></i>
                          <span>Mises Bas</span>
                      </a>
                      <a href="{{ route('naissances.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('naissances.*') ? 'active' : '' }}">
                          <i class="bi bi-egg-fill"></i>
                          <span>Naissances</span>
                      </a>
                      <div class="mobile-nav-divider"></div>
                      <a href="{{ route('saillies.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('saillies.*') ? 'active' : '' }}">
                          <i class="bi bi-heart"></i>
                          <span>Saillies</span>
                      </a>
                      <a href="{{ route('sales.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                          <i class="bi bi-cart"></i>
                          <span>Ventes</span>
                      </a>
                      <div class="mobile-nav-divider"></div>
                      <a href="{{ route('activites.index') }}"
                          class="mobile-nav-link {{ request()->routeIs('activites.*') ? 'active' : '' }}">
                          <i class="bi bi-clock-history"></i>
                          <span>Activités</span>
                      </a>

                      <a href="{{ route('notifications.index') }}" class="mobile-nav-link">
                          <i class="bi bi-bell"></i>
                          <span>Notifications</span>
                          @php
                              $unread = \App\Models\Notification::where('user_id', auth()->id())
                                  ->where('is_read', false)
                                  ->count();
                          @endphp
                          @if ($unread > 0)
                              <span class="notification-badge" style="position: static; margin-left: auto;">
                                  {{ $unread > 99 ? '99+' : $unread }}
                              </span>
                          @endif
                      </a>
                      <a href="{{ route('settings.index') }}" class="mobile-nav-link">
                          <i class="bi bi-gear"></i>
                          <span>Paramètres</span>
                      </a>
                      {{-- <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                          <i class="bi bi-person"></i>
                          <span>Mon Profil</span>
                      </a> --}}

                      <div class="mobile-nav-divider"></div>
                      <div class="mobile-nav-link" style="cursor: default; background: var(--surface-alt);">
                          <div class="user-avatar" style="width: 28px; height: 28px; font-size: 13px;">
                              {{ substr(auth()->user()->name, 0, 1) }}
                          </div>
                          <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
                      </div>
                      <a href="{{ route('profile.edit') }}" class="mobile-nav-link" style="padding-left: 52px;">
                          <i class="bi bi-person"></i>
                          <span>Mon Profil</span>
                      </a>
                      <div class="mobile-nav-divider"></div>
                      <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <button type="submit" class1="mobile-nav-link"
                              style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; color: var(--accent-red);">
                              <i class="bi bi-box-arrow-right"></i>
                              <span>Déconnexion</span>
                          </button>
                      </form>
                  </div>
              </div>
          @endauth
      </header>

      <main class="cuni-main">
          @yield('content')
      </main>

      <footer class="cuni-footer">
          <div class="footer-container">
              <div class="footer-grid">
                  <div class="footer-brand">
                      <div class="footer-logo">
                          <div class="footer-logo-icon">
                              <svg viewBox="0 0 40 40" fill="none">
                                  <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                                  <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                              </svg>
                          </div>
                          <div class="footer-logo-text">CuniApp <span>Élevage</span></div>
                      </div>
                      <p class="footer-tagline"> La solution complète pour la gestion intelligente de votre élevage de
                          lapins. Suivez vos reproductions, naissances et performances en toute simplicité. </p>
                  </div>
                  <div class="footer-section">
                      <h4><i class="bi bi-compass"></i> Navigation</h4>
                      <ul class="footer-links">
                          <li><a href="{{ route('dashboard') }}"><i class="bi bi-chevron-right"></i> Tableau de
                                  bord</a></li>
                          <li><a href="{{ route('males.index') }}"><i class="bi bi-chevron-right"></i> Mâles</a></li>
                          <li><a href="{{ route('femelles.index') }}"><i class="bi bi-chevron-right"></i>
                                  Femelles</a>
                          </li>
                          <li><a href="{{ route('lapins.index') }}"><i class="bi bi-chevron-right"></i> Tous les
                                  Lapins</a></li>
                          <li><a href="{{ route('mises-bas.index') }}"><i class="bi bi-chevron-right"></i> Mises
                                  Bas</a></li>
                      </ul>
                  </div>
                  <div class="footer-section">
                      <h4><i class="bi bi-briefcase"></i> Gestion</h4>
                      <ul class="footer-links">
                          <li><a href="{{ route('saillies.index') }}"><i class="bi bi-chevron-right"></i>
                                  Saillies</a>
                          </li>
                          <li><a href="{{ route('sales.index') }}"><i class="bi bi-chevron-right"></i> Ventes</a>
                          </li>
                          <li><a href="{{ route('notifications.index') }}"><i class="bi bi-chevron-right"></i>
                                  Notifications</a></li>
                          <li><a href="{{ route('settings.index') }}"><i class="bi bi-chevron-right"></i>
                                  Paramètres</a></li>
                          <li><a href="{{ route('profile.edit') }}"><i class="bi bi-chevron-right"></i> Mon
                                  Profil</a>
                          </li>
                      </ul>
                  </div>
                  <div class="footer-section">
                      <h4><i class="bi bi-envelope"></i> Contact</h4>
                      <div class="footer-contact">
                          <div class="footer-contact-item">
                              <i class="bi bi-geo-alt"></i>
                              <span>{{ \App\Models\Setting::get('farm_address', 'Adresse non renseignée') }}</span>
                          </div>
                          <div class="footer-contact-item">
                              <i class="bi bi-telephone"></i>
                              <a
                                  href="tel:{{ \App\Models\Setting::get('farm_phone', '') }}">{{ \App\Models\Setting::get('farm_phone', 'Non renseigné') }}</a>
                          </div>
                          <div class="footer-contact-item">
                              <i class="bi bi-envelope"></i>
                              <a
                                  href="mailto:{{ \App\Models\Setting::get('farm_email', config('mail.from.address')) }}">{{ \App\Models\Setting::get('farm_email', config('mail.from.address')) }}</a>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="footer-bottom">
                  <div class="footer-copyright">
                      &copy; {{ date('Y') }} <a href="{{ route('dashboard') }}">CuniApp Élevage</a>. Tous droits
                      réservés.
                  </div>
                  <div class="footer-legal">
                      <a href="{{ route('privacy') }}">Confidentialité</a>
                      <a href="{{ route('terms') }}">Conditions</a>
                      <a href="{{ route('contact') }}">Support</a>
                  </div>
              </div>
          </div>
      </footer>

      <button id="backToTop" class="back-to-top" title="Retour en haut">
          <i class="bi bi-arrow-up-short"></i>
      </button>

      @stack('scripts')
      @include('components.modal-system')
      <script>
          function getSystemTheme() {
              return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
          }

          function initTheme() {
              const userTheme = '{{ auth()->check() ? auth()->user()->theme : 'system' }}';
              const savedTheme = localStorage.getItem('color-theme') || userTheme || 'system';
              let currentTheme = savedTheme;
              if (currentTheme === 'system') {
                  currentTheme = getSystemTheme();
              }
              applyThemeVisuals(currentTheme, savedTheme);
          }

          function applyThemeVisuals(actualTheme, savedTheme) {
              if (actualTheme === 'dark') {
                  document.documentElement.classList.add('theme-dark');
                  const themeText = document.getElementById('theme-text');
                  const themeIcon = document.getElementById('theme-icon-main');
                  if (themeText) themeText.innerText = 'Sombre';
                  if (themeIcon) themeIcon.className = 'bi bi-moon-fill';
              } else {
                  document.documentElement.classList.remove('theme-dark');
                  const themeText = document.getElementById('theme-text');
                  const themeIcon = document.getElementById('theme-icon-main');
                  if (themeText) themeText.innerText = savedTheme === 'system' ? 'Système' : 'Clair';
                  if (themeIcon) themeIcon.className = savedTheme === 'system' ? 'bi bi-display' : 'bi bi-sun';
              }
              const themeBadge = document.getElementById('theme-badge');
              if (themeBadge) {
                  const badgeText = themeBadge.querySelector('span');
                  if (badgeText) {
                      const themeValue = '{{ auth()->check() ? auth()->user()->theme : 'system' }}';
                      badgeText.innerText = themeValue === 'system' ? 'Système' : (themeValue === 'light' ? 'Clair' :
                          'Sombre');
                  }
              }
          }

          function setTheme(theme) {
              localStorage.setItem('color-theme', theme);
              if ({{ auth()->check() ? 'true' : 'false' }}) {
                  fetch('{{ route('settings.update') }}', {
                          method: 'POST',
                          headers: {
                              'Content-Type': 'application/json',
                              'X-CSRF-TOKEN': '{{ csrf_token() }}'
                          },
                          body: JSON.stringify({
                              theme: theme
                          })
                      })
                      .then(response => response.json())
                      .then(data => {
                          console.log('Theme updated:', data);
                      })
                      .catch(error => {
                          console.error('Theme update failed:', error);
                      });
              }
              let applyTheme = theme;
              if (theme === 'system') {
                  applyTheme = getSystemTheme();
              }
              applyThemeVisuals(applyTheme, theme);
              const label = theme === 'system' ? 'Système' : (theme === 'light' ? 'Clair' : 'Sombre');
              showToast('Thème mis à jour: ' + label);
              setTimeout(() => {
                  window.location.reload();
              }, 500);
          }

          window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
              const savedTheme = localStorage.getItem('color-theme');
              if (!savedTheme || savedTheme === 'system') {
                  initTheme();
              }
          });

          document.addEventListener('click', function(e) {
              const userDropdown = document.getElementById('userDropdown');
              const moreDropdown = document.getElementById('moreDropdown');
              if (userDropdown && !e.target.closest('.user-profile-dropdown')) {
                  userDropdown.classList.remove('show');
              }
              if (moreDropdown && !e.target.closest('.dropdown-container')) {
                  moreDropdown.classList.remove('show');
              }
          });

          initTheme();

          function showToast(message, type = 'info') {
              const toast = document.createElement('div');
              toast.style.cssText = `
                position: fixed;
                bottom: 100px;
                right: 30px;
                background: var(--surface);
                border: 1px solid var(--surface-border);
                border-left: 4px solid ${type === 'success' ? 'var(--accent-green)' : 'var(--primary)'};
                padding: 16px 24px;
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-lg);
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideInRight 0.3s ease;
                max-width: 400px;
            `;
              toast.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'info-circle-fill'}" style="color: ${type === 'success' ? 'var(--accent-green)' : 'var(--primary)'}; font-size: 20px;"></i>
                <span style="color: var(--text-primary); font-size: 14px; font-weight: 500;">${message}</span>
            `;
              document.body.appendChild(toast);
              setTimeout(() => {
                  toast.style.animation = 'slideOutRight 0.3s ease';
                  setTimeout(() => toast.remove(), 300);
              }, 3000);
          }

          const style = document.createElement('style');
          style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
          document.head.appendChild(style);

          function toggleMoreDropdown(event) {
              if (event) {
                  event.stopPropagation();
                  event.preventDefault();
              }
              const moreDropdown = document.getElementById('moreDropdown');
              const userDropdown = document.getElementById('userDropdown');
              const mobileNav = document.getElementById('mobileNavOverlay');

              if (userDropdown) userDropdown.classList.remove('show');
              if (mobileNav) mobileNav.classList.remove('active');

              if (moreDropdown) {
                  const isShowing = moreDropdown.classList.contains('show');
                  // Close all dropdowns first
                  document.querySelectorAll('.dropdown-menu-custom').forEach(d => d.classList.remove('show'));
                  // Toggle current dropdown
                  if (!isShowing) {
                      moreDropdown.classList.add('show');
                  }
              }
          }

          function toggleUserDropdown(event) {
              if (event) {
                  event.stopPropagation();
                  event.preventDefault();
              }
              const userDropdown = document.getElementById('userDropdown');
              const moreDropdown = document.getElementById('moreDropdown');
              const mobileNav = document.getElementById('mobileNavOverlay');

              if (moreDropdown) moreDropdown.classList.remove('show');
              if (mobileNav) mobileNav.classList.remove('active');

              if (userDropdown) {
                  const isShowing = userDropdown.classList.contains('show');
                  document.querySelectorAll('.dropdown-menu-custom').forEach(d => d.classList.remove('show'));
                  if (!isShowing) {
                      userDropdown.classList.add('show');
                  }
              }
          }

          function toggleMobileNav() {
              const mobileNav = document.getElementById('mobileNavOverlay');
              const userDropdown = document.getElementById('userDropdown');
              const moreDropdown = document.getElementById('moreDropdown');
              if (userDropdown) userDropdown.classList.remove('show');
              if (moreDropdown) moreDropdown.classList.remove('show');
              if (mobileNav) {
                  mobileNav.classList.toggle('active');
                  document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
              }
          }

          const backToTop = document.getElementById('backToTop');
          window.addEventListener('scroll', () => {
              if (window.scrollY > 300) {
                  backToTop.classList.add('show');
              } else {
                  backToTop.classList.remove('show');
              }
          });

          backToTop.addEventListener('click', () => {
              window.scrollTo({
                  top: 0,
                  behavior: 'smooth'
              });
          });

          // Update the document click handler
          document.addEventListener('click', function(e) {
              const userDropdown = document.getElementById('userDropdown');
              const moreDropdown = document.getElementById('moreDropdown');
              const mobileNav = document.getElementById('mobileNavOverlay');
              const mobileTrigger = document.querySelector('.mobile-menu-trigger');

              // Close user dropdown if clicking outside
              if (userDropdown && !e.target.closest('.user-profile-dropdown')) {
                  userDropdown.classList.remove('show');
              }

              // Close more dropdown if clicking outside
              if (moreDropdown && !e.target.closest('.dropdown-container')) {
                  moreDropdown.classList.remove('show');
              }

              // Close mobile nav if clicking outside
              if (mobileNav && mobileTrigger &&
                  !e.target.closest('.mobile-menu-trigger') &&
                  !e.target.closest('.mobile-nav-overlay')) {
                  mobileNav.classList.remove('active');
                  document.body.style.overflow = '';
              }
          });
      </script>

  </body>

  </html>
