<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('') }}assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Rocker</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ url('/dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>

        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Application</div>
            </a>
            <ul>
                <li>
                    <a href="{{ url('/product') }}"><i class="bx bx-right-arrow-alt"></i>Product</a>
                </li>
                <li>
                    <a href="{{ url('/request-market') }}"><i class="bx bx-right-arrow-alt"></i>Request Market</a>
                </li>
                <li>
                    <a href="{{ url('/topup-diamon') }}"><i class="bx bx-right-arrow-alt"></i>Topup Diamon</a>
                </li>
                <li>
                    <a href="{{ url('/topup-pangan') }}"><i class="bx bx-right-arrow-alt"></i>Topup Pangan</a>
                </li>
            </ul>
        </li>
        <li class="menu-label">UI Elements</li>

        <li>
            <a href="https://themeforest.net/user/codervent" target="_blank">
                <div class="parent-icon"><i class="bx bx-support"></i>
                </div>
                <div class="menu-title">Support</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
