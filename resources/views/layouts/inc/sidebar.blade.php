@if (Auth::check() && Auth::user()->role_as !== 0 )
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header" style="background-color: #348eed">
        <h4 class="ms-1 font-weight-bold text-white text-center p-4">Vit Equinee System</h4>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse h-auto w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav p-auto">
            @if (Auth::check() && Auth::user()->role_as == 1 )
            <li class="nav-item m-5% rounded-s {{Request::is('Dashboard') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white" href="{{ url('Dashboard')}}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">dashboard</i>
                </div>
                <span class="nav-link-text ms-1">Owner Dashboard</span>
                </a>
            </li>
            @elseif(Auth::check())
            <li class="nav-item m-5% rounded-s {{Request::is('Supervisor') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white" href="{{ url('Supervisor')}}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">dashboard</i>
                </div>
                <span class="nav-link-text ms-1">Manager Dashboard</span>
                </a>
            </li>
            @endif
            <li class="nav-item {{Request::is('accountant') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white" href="{{ url('accountant')}}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">dashboard</i>
                </div>
                <span class="nav-link-text ms-1">Accountants</span>
                </a>
            </li>
            <li class="nav-item {{Request::is('Notes') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white" href="{{ url('Notes')}}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">dashboard</i>
                </div>
                <span class="nav-link-text ms-1">Notes</span>
                </a>
            </li>

            <li class="nav-item {{Request::is('add-task') ? 'active bg-gradient-info':''}}">
            <a class="nav-link text-white " href="{{ url('add-task')}}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">library_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Add Tasks</span>
                </a>
            </li>
            <li class="nav-item {{Request::is('add-visit') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white " href="{{ url('add-visit')}}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">library_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Add Visits</span>
                </a>
            </li>
            <li class="nav-item {{Request::is('add-treatment') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white " href="{{ url('add-treatment')}}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">library_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Add Treatments</span>
                </a>
            </li>
            <li class="nav-item {{Request::is('Feeding') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white " href="{{ url('Feeding')}}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">library_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Add Feeding</span>
                </a>
            </li>
            <li class="nav-item {{Request::is('Bedding') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white " href="{{ url('Bedding')}}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">library_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Add Bedding</span>
                </a>
            </li>
                <li class="nav-item {{Request::is('add-internalinvoice') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white " href="{{ url('add-internalinvoice')}}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">library_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Add Internal Invoice</span>
                </a>
            </li>
            <li class="nav-item {{Request::is('add-externalinvoice') ? 'active bg-gradient-info':''}}">
                <a class="nav-link text-white " href="{{ url('add-externalinvoice')}}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">library_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Add External Invoice</span>
                </a>
            </li>

      </ul>
    </div>
</aside>
@endif

