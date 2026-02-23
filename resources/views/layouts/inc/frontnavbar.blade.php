<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <nav class="col">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Welcome : -</li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                @if (Auth::check() && Auth::user()->role_as == 1)
                 Owner
                @elseif (Auth::check() && Auth::user()->major == 1)
                Accountant
                @elseif (Auth::check() && Auth::user()->major == 2)
                Dr
                @elseif (Auth::check() && Auth::user()->major == 3)
                Horse Groom
                @elseif (Auth::check() && Auth::user()->major == 4)
                Farrier
                @else
                Mr
                @endif
                </li>
            </ol>
            <h5 class="font-weight-bolder text-capitalize mb-0">
                @if (Auth::check())
                    {{ Auth::user()->name }}
                @endif
            </h5>
        </nav>
        @if (Auth::check() && Auth::user()->role_as !== 0)
        <div class="search-bar">
            <form action="{{ url('searchstud') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="search" class="form-control" name="stud_name" id="search_stud" placeholder="Search" required aria-describedby="basic-addon1">
                    <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
        @endif
        <nav class="col">
            <div class="col">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center float-end">
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <li class="nav-item dropdown">
                              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                  <i class="fa fa-user me-sm-1 "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (Auth::check() && Auth::user()->role_as !== 0)
                                      <a class="dropdown-item" href="{{ url('my-task/'.Auth::user()->id) }}"> My Tasks </a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="{{ url('my-visit/'.Auth::user()->id) }}"> My Visits </a>
                                      <div class="dropdown-divider"></div>
                                    @endif
                                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"> Logout</i>
                                  </a>
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                      @csrf
                                  </form>
                              </div>
                          </li>
                          </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</nav>
