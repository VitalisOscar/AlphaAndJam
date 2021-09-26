function Sidebar(){
    return <div class="container-fluid px-0 px-sm-3">
    <aside class="sidenav d-lg-block d-none" id="sidenav" onclick="if(event.target == this){ this.classList.remove('open') }">
        <div class="px-3 py-4">
            <div class="mb-3">
                <div><strong>James Maina&nbsp;(Safaricom PLC)</strong>
                </div>
                <div class="mb-2">safaricom@test.com</div>
                <div>
                    <a href="http://localhost:8000/logout" class="btn btn-outline-danger btn-block shadow-none py-2"><i class="fa fa-power-off"></i> Sign Out</a>
                </div>
            </div>

            <div class="sidenav-items mb-4">
                <a href="http://localhost:8000/platform"  class="active" ><i class="fa fa-user bg-default"></i>Dashboard</a>
                <a href="http://localhost:8000/platform/adverts/create"  ><i class="fa fa-upload bg-primary"></i>Upload Advert</a>
                <a href="http://localhost:8000/platform/adverts/drafts" class="d-none " >
                    <i class="fa fa-clock-o bg-warning"></i>Drafts
                                                                </a>
                <a href="http://localhost:8000/platform/adverts/pending"  ><i class="fa fa-clock-o bg-info"></i>Pending Approval</a>
                <a href="http://localhost:8000/platform/adverts/approved"  ><i class="fa fa-check bg-success"></i>Approved Ads</a>
                <a href="http://localhost:8000/platform/adverts/declined"  ><i class="fa fa-times bg-danger"></i>Declined Ads</a>
                <a href="http://localhost:8000/account/profile"  ><i class="fa fa-user bg-indigo"></i>My Account</a>
                <a href="http://localhost:8000/account/invoices"  ><i class="fa fa-money bg-warning"></i>My Invoices</a>
            </div>

            <hr class="my-3" />

            <div>
                <a href="https://oriscop.com" target="_blank">
                    <img src="http://localhost:8000/img/logo.png" class="d-block mx-auto" alt="Oriscop" />
                </a>
            </div>
        </div>
    </aside>
    </div>
}

export default Sidebar;
