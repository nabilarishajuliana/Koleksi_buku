<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo" href="#">
            <img src="{{ asset('template/assets/images/logo.svg') }}" alt="logo" />
        </a>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-center"
                    href="{{ route('report.undangan') }}"
                    style="
                    width:35px;
                    height:35px;
                    background-color:#6C5CE7;
                    border-radius:50%;
                    transition:0.3s;
                    color:white;"
                    onmouseover="this.style.backgroundColor='#5a4bcf'"
                    onmouseout="this.style.backgroundColor='#6C5CE7'"
                    title="Download Undangan PDF">

                    <i class="mdi mdi-email-outline"></i>
                </a>
            </li>

            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center"
                    data-bs-toggle="dropdown"
                    style="gap:10px;">

                    <!-- FOTO PROFILE -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6C5CE7&color=fff"
                        alt="profile"
                        style="
                        width:35px;
                        height:35px;
                        border-radius:50%;
                        object-fit:cover;
                        border:2px solid #6C5CE7;
                    ">

                    <!-- NAMA USER -->
                    <span class="mb-0 text-black">
                        {{ Auth::user()->name }}
                    </span>
                </a>

                <div class="dropdown-menu navbar-dropdown">
                    <a class="dropdown-item"
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>

        </ul>
    </div>
</nav>