 <li class="nav-item dropdown dropdown-large">
     <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button"
         data-bs-toggle="dropdown" aria-expanded="false">
         <span class="alert-count">{{ notif()['count'] }}</span>
         <i class='bx bx-bell'></i>
     </a>
     <div class="dropdown-menu dropdown-menu-end">
         <a href="javascript:;">
             <div class="msg-header">
                 <p class="msg-header-title">Notifications</p>
                 <p class="msg-header-clear ms-auto">Marks all as read</p>
             </div>
         </a>
         <div class="header-notifications-list">
             @foreach (notif()['data'] as $item)
                 <a class="dropdown-item" href="javascript:;">
                     <div class="d-flex align-items-center">
                         {{-- <div class="notify bg-light-success text-success">
                            <i class='bx bx-check-square'></i>
                         </div> --}}
                         <div class="flex-grow-1">
                             <h6 class="msg-name">{{ $item->title }}<span
                                     class="msg-time float-end">{{ $item->created_at->diffForHumans() }}</span></h6>
                             <p class="msg-info">{!! tambahEnter($item->message) !!}
                             </p>
                         </div>
                     </div>
                 </a>
             @endforeach

         </div>
         <a href="javascript:;">
             <div class="text-center msg-footer">View All Notifications</div>
         </a>
     </div>
 </li>
