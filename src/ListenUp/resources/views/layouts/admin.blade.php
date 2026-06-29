@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    /* Premium Floating Sidebar Styles */
    :root {
        --admin-primary: #0b1b3d;
        --admin-accent: #7c3aed;
        --admin-accent-glow: rgba(124, 58, 237, 0.4);
        --admin-text-gray: #94a3b8;
        --sidebar-collapsed-width: 70px;
        --sidebar-expanded-width: 280px;
    }

    .admin-container {
        position: relative;
        width: 100%;
    }

    /* Floating Sidebar */
    .floating-sidebar {
        position: fixed;
        top: 100px;
        left: 24px;
        width: var(--sidebar-collapsed-width);
        height: 560px;
        border-radius: 24px;
        background: rgba(11, 27, 61, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        z-index: 1000;
        transition: width 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* Expand Sidebar on Hover */
    .floating-sidebar:hover {
        width: var(--sidebar-expanded-width);
        box-shadow: 0 20px 50px rgba(11, 27, 61, 0.35);
        border-color: rgba(255, 255, 255, 0.15);
    }

    /* Sidebar Header (Logo) */
    .sidebar-header {
        height: 64px;
        min-height: 64px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        cursor: pointer;
    }

    .logo-container {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-accent) 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        transition: transform 0.5s ease;
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0% {
            box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(124, 58, 237, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(124, 58, 237, 0);
        }
    }

    .floating-sidebar:hover .logo-container {
        transform: rotate(360deg);
    }

    .logo-text {
        margin-left: 12px;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.15rem;
        color: white;
        white-space: nowrap;
        opacity: 0;
        max-width: 0;
        overflow: hidden;
        transition: opacity 0.3s ease, max-width 0.3s ease;
    }

    .floating-sidebar:hover .logo-text {
        opacity: 1;
        max-width: 200px;
    }

    .logo-badge {
        font-size: 8px;
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        padding: 1px 5px;
        border-radius: 4px;
        margin-left: 6px;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Sidebar Content / Links */
    .sidebar-content {
        flex-grow: 1;
        padding: 16px 8px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Custom thin scrollbar for sidebar items if they overflow */
    .sidebar-content::-webkit-scrollbar {
        width: 3px;
    }
    .sidebar-content::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar-content::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .sidebar-menu-link {
        display: flex;
        align-items: center;
        padding: 10px 8px;
        border-radius: 12px;
        color: var(--admin-text-gray);
        text-decoration: none !important;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .sidebar-menu-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .floating-sidebar:hover .sidebar-menu-link:hover {
        transform: translateX(4px);
    }

    .sidebar-menu-link.active {
        background: linear-gradient(135deg, var(--admin-accent) 0%, #4f46e5 100%);
        color: white;
        box-shadow: 0 4px 15px var(--admin-accent-glow);
    }

    .link-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }

    .sidebar-menu-link:hover .link-icon {
        transform: scale(1.1);
    }

    .link-text {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0;
        max-width: 0;
        overflow: hidden;
        transition: opacity 0.3s ease, max-width 0.3s ease;
        margin-left: 0;
    }

    .floating-sidebar:hover .link-text {
        opacity: 1;
        max-width: 200px;
        margin-left: 12px;
    }

    /* Sidebar Divider */
    .sidebar-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.05);
        margin: 6px 8px;
    }

    /* Main Content Shift */
    .admin-main-content {
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .admin-main-content {
            padding-left: 80px; /* Space reserved for collapsed sidebar (70px + 10px spacing) */
        }
    }

    /* Extra styles for nice cards */
    .admin-card-wrapper {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        background: white;
    }
    .dark .admin-card-wrapper {
        background: #0d1425;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Mobile toggle and responsive styles */
    @media (max-width: 768px) {
        .floating-sidebar {
            top: auto;
            bottom: 20px;
            left: 20px;
            width: 54px;
            height: 54px;
            border-radius: 27px;
        }
        .floating-sidebar:hover {
            width: calc(100% - 40px);
            height: 490px;
            border-radius: 20px;
        }
        .admin-main-content {
            padding-left: 0;
        }
        .link-text {
            display: none;
        }
        .floating-sidebar:hover .link-text {
            display: block;
            opacity: 1;
            max-width: 200px;
            margin-left: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 admin-container">
    <!-- Floating Admin Sidebar -->
    <div class="floating-sidebar">
        <!-- Header / Logo -->
        <div class="sidebar-header">
            <div class="logo-container">
                <i class="fas fa-headphones text-sm"></i>
            </div>
            <span class="logo-text">ListenUp <span class="logo-badge">Admin</span></span>
        </div>

        <!-- Menu items -->
        <div class="sidebar-content">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt link-icon"></i>
                <span class="link-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.user.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="fas fa-users link-icon"></i>
                <span class="link-text">Quản lý Người dùng</span>
            </a>
            <a href="{{ route('admin.chude.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.chude.*') ? 'active' : '' }}">
                <i class="fas fa-tags link-icon"></i>
                <span class="link-text">Quản lý Chủ đề</span>
            </a>
            <a href="{{ route('admin.capdonghe.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.capdonghe.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group link-icon"></i>
                <span class="link-text">Quản lý Cấp độ nghe</span>
            </a>
            <a href="{{ route('admin.baitest.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.baitest.*') ? 'active' : '' }}">
                <i class="fas fa-book link-icon"></i>
                <span class="link-text">Quản lý Bài test</span>
            </a>
            <a href="{{ route('admin.bandophieuluu.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.bandophieuluu.*') ? 'active' : '' }}">
                <i class="fas fa-map link-icon"></i>
                <span class="link-text">Quản lý Bản đồ</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="sidebar-menu-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-edit link-icon"></i>
                <span class="link-text">Hồ sơ cá nhân</span>
            </a>

            <div class="sidebar-divider"></div>

            <!-- Additional Quick Links -->
            <a href="{{ route('user.dashboard') }}" class="sidebar-menu-link">
                <i class="fas fa-home link-icon"></i>
                <span class="link-text">Về trang User</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="sidebar-menu-link bg-transparent border-0 w-100 text-danger hover:bg-danger/10">
                    <i class="fas fa-sign-out-alt link-icon text-danger"></i>
                    <span class="link-text">Đăng xuất</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="admin-main-content">
        <div class="card admin-card-wrapper shadow-sm">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('admin_content')
            </div>
        </div>
    </div>
</div>
@endsection
