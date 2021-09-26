function Header(){
    return <div class="bg-white sticky-top">

    <header class="navbar py-2 px-0 px-sm-3 section-shaped position-relative">

        <div class="shape shape-light position-absolute top-0 bottom-0 right-0 left-0 bg-gradient-danger shape-style-1">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="container-fluid">
            <button class="navbar-toggler text-white d-none d-md-inline-block d-lg-none mr-3" onclick="$('#sidenav').toggleClass('open')">
                <i class="fa fa-bars"></i>
            </button>

            <a href="{{ route('home') }}" class="navbar-brand mr-auto">
                Alpha
            </a>

            <div class="ml-auto d-flex align-items-center">
                <div class="d-none d-md-flex align-items-center">
                    <div class="links mr-3">
                        <a href="">Dashboard</a>
                        <a href="">View Presence</a>
                    </div>
                    <a href="" class="btn btn-white shadow-none py-2"><i class="fa fa-dollar mr-1"></i>Your Invoices</a>
                    <a href="" class="btn btn-default shadow-none py-2"><i class="fa fa-upload mr-1"></i>Upload an Ad</a>
                </div>

                <button class="navbar-toggler text-white d-md-none" onclick="$('#sidenav').toggleClass('open')">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
</div>
}

export default Header;
