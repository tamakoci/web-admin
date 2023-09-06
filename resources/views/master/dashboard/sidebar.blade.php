<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('') }}ayamku.png" class="logo-icon" alt="logo icon" style="min-width: 100px;">
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
            <a href="{{ url(auth()->user()->user_role == 1 ? 'user/dashboard' : 'admin/dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @if (auth()->user()->user_role == 1)
            {{-- <li>
                <a href="{{ url('user/bank-account') }}">
                    <div class="parent-icon"><i class='bx bx-building-house'></i>
                    </div>
                    <div class="menu-title">Bank Account</div>
                </a>
            </li> --}}
            <li>
                <a href="{{ url('user/withdrawl') }}">
                    <div class="parent-icon"><i class='bx bx-money'></i>
                    </div>
                    <div class="menu-title">Withdrawal Now</div>
                </a>
            </li>
            {{-- <li>
                <a href="{{ url('user/referal') }}">
                    <div class="parent-icon"><i class='bx bx-money'></i>
                    </div>
                    <div class="menu-title">Referals</div>
                </a>
            </li> --}}
        @endif

        @if (auth()->user()->user_role == 2)
            <li class="menu-label">Admin Area</li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Master data</div>
                </a>
                <ul>
                    <li>
                        {{-- <a href="{{ url('admin/product') }}"><i class="bx bx-right-arrow-alt"></i>Product Ternak</a> --}}
                        <a href="{{ url('admin/telur') }}"><i class="bx bx-right-arrow-alt"></i>Harga Telur</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/ternak') }}"><i class="bx bx-right-arrow-alt"></i>Ternak</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/pakan-ternak') }}"><i class="bx bx-right-arrow-alt"></i>Pakan Ternak</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/topup-diamon') }}"><i class="bx bx-right-arrow-alt"></i>Topup Diamon</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/topup-pakan') }}"><i class="bx bx-right-arrow-alt"></i>Topup Pakan</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/request-market') }}"><i class="bx bx-right-arrow-alt"></i>Market</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/bank') }}"><i class="bx bx-right-arrow-alt"></i>Bank</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ url('/admin/notif') }}">
                    <div class="parent-icon"><i class='bx bx-bell'></i>
                    </div>
                    <div class="menu-title">Notifications</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-transfer-alt"></i>
                    </div>
                    <div class="menu-title">Transaction</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ url('/admin/ternak-user') }}">
                            <i class="bx bx-right-arrow-alt"></i>Ternak User</a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/transaction') }}"><i class="bx bx-right-arrow-alt"></i>Log</a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="{{ url('/admin/manage-user') }}">
                    <div class="parent-icon"><i class='bx bx-user'></i>
                    </div>
                    <div class="menu-title">Manage User</div>
                </a>
            </li>
        @endif
    </ul>
    <!--end navigation-->
</div>
