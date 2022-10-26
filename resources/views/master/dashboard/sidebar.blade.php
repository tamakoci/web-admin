<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('') }}logo.png" class="logo-icon" alt="logo icon" style="min-width: 100px;">
        </div>
        <div>
            {{-- <h4 class="logo-text">Tamakoci</h4> --}}
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
            <a href="{{ url('/referal') }}">
                <div class="parent-icon"><i class='bx bx-money'></i>
                </div>
                <div class="menu-title">Referals</div>
            </a>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Master</div>
            </a>
            <ul>
                <li>
                    <a href="{{ url('/product') }}"><i class="bx bx-right-arrow-alt"></i>Product Ternak</a>
                </li>
                <li>
                    <a href="{{ url('/ternak') }}"><i class="bx bx-right-arrow-alt"></i>Ternak</a>
                </li>
                <li>
                    <a href="{{ url('/pakan-ternak') }}"><i class="bx bx-right-arrow-alt"></i>Pakan Ternak</a>
                </li>
                <li>
                    <a href="{{ url('/topup-diamon') }}"><i class="bx bx-right-arrow-alt"></i>Topup Diamon</a>
                </li>
                <li>
                    <a href="{{ url('/topup-pakan') }}"><i class="bx bx-right-arrow-alt"></i>Topup Pakan</a>
                </li>
                <li>
                    <a href="{{ url('/request-market') }}"><i class="bx bx-right-arrow-alt"></i>Market</a>
                </li>
                <li>
                    <a href="{{ url('/bank') }}"><i class="bx bx-right-arrow-alt"></i>Bank</a>
                </li>
            </ul>
        </li>
        <li class="menu-label">Admin Area</li>
        <li>
            <a href="{{ url('/admin/transaction') }}">
                <div class="parent-icon"><i class='bx bx-transfer-alt'></i>
                </div>
                <div class="menu-title">Transaction Log</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Admin</div>
            </a>
            <ul>
                <li>
                    <a href="{{ url('/admin/manage-user') }}"><i class="bx bx-right-arrow-alt"></i>Manage User</a>
                </li>
            </ul>
        </li>
        {{-- <li>
            <a href="https://themeforest.net/user/codervent" target="_blank">
                <div class="parent-icon"><i class="bx bx-support"></i>
                </div>
                <div class="menu-title">Support</div>
            </a>
        </li> --}}
    </ul>
    <!--end navigation-->
</div>
