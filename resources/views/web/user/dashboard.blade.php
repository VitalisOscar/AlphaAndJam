<?php
    $repository = resolve(\App\Repository\AdvertRepository::class);
    $ads = $repository->getRecentAds(3);

    $summary = $repository->getSummary();

    $approved_ads = $summary['approved'];
    $declined_ads = $summary['declined'];
    $pending_ads = $summary['pending'];

    $user = auth()->user();
    $verification = $user->verification;
?>

@extends('web.base')

@section('title', config('app.name').' | User Dashboard')

@section('section_heading', 'Dashboard - '.$user->name)

@section('content')

    <div class="row">
        <div class="col-lg-9">

            <div class="row mb-2 d-none d-sm-flex">

                <div class="col-md-4 mb-4">
                    <a href="{{ route('web.adverts.approved') }}" class="shadow-lg text-white rounded-lg d-block bg-purple">
                        <div class="p-3">
                            <h4 class="font-weight-600 mb-2 text-white">Approved Ads</h4>

                            <div class="d-flex align-items-center">
                                <strong style="font-size: 1.5em">{{ $approved_ads }}</strong>
                                <i class="fa fa-check-circle float-right ml-auto" style="font-size: 1.5em"></i>
                            </div>
                        </div>

                        <div class="border-top border-white text-right small font-weight-600 p-3">
                            View All <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4">
                    <a href="{{ route('web.adverts.declined') }}" class="shadow-lg text-white rounded-lg d-block bg-gradient-danger">
                        <div class="p-3">
                            <h4 class="font-weight-600 mb-2 text-white">Declined Ads</h4>

                            <div class="d-flex align-items-center">
                                <strong style="font-size: 1.5em">{{ $declined_ads }}</strong>
                                <i class="fa fa-close float-right ml-auto" style="font-size: 1.5em"></i>
                            </div>
                        </div>

                        <div class="border-top border-white text-right small font-weight-600 p-3">
                            View All <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4">
                    <a href="{{ route('web.adverts.pending') }}" class="shadow-lg text-white rounded-lg d-block bg-default">
                        <div class="p-3">
                            <h4 class="font-weight-600 mb-2 text-white">Pending</h4>

                            <div class="d-flex align-items-center">
                                <strong style="font-size: 1.5em">{{ $pending_ads }}</strong>
                                <i class="fa fa-clock-o float-right ml-auto" style="font-size: 1.5em"></i>
                            </div>
                        </div>

                        <div class="border-top border-white text-right small font-weight-600 p-3">
                            View All <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mb-4 d-sm-none">
                <div class="col-x6">
                    <a href="{{ route('web.adverts.approved') }}" class="d-flex align-items-center border rounded bg-white mb-3 p-3">
                        <span class="icon icon-shape bg-success text-white">
                            <i class="fa fa-check"></i>
                        </span>

                        <div class="ml-3 mr-3">
                            <div class="text-body">Approved Ads</div>
                            <h5 class="mb-0"><strong>{{ $approved_ads }}</strong></h5>
                        </div>

                        <span class="ml-auto mr-1 float-right">
                            <i class="fa fa-chevron-right text-muted"></i>
                        </span>
                    </a>
                </div>

                <div class="col-x6">
                    <a href="{{ route('web.adverts.declined') }}" class="d-flex align-items-center border rounded bg-white mb-3 p-3">
                        <span class="icon icon-shape bg-gradient-danger text-white">
                            <i class="fa fa-times"></i>
                        </span>

                        <div class="ml-3 mr-3">
                            <div class="text-body">Declined Ads</div>
                            <h5 class="mb-0"><strong>{{ $declined_ads }}</strong></h5>
                        </div>

                        <span class="ml-auto mr-1 float-right">
                            <i class="fa fa-chevron-right text-muted"></i>
                        </span>
                    </a>
                </div>

                <div class="col-x6">
                    <a href="{{ route('web.adverts.pending') }}" class="d-flex align-items-center border rounded bg-white mb-3 p-3">
                        <span class="icon icon-shape bg-info text-white">
                            <i class="fa fa-clock-o"></i>
                        </span>

                        <div class="ml-3 mr-3">
                            <div class="text-body">Pending Approval</div>
                            <h5 class="mb-0"><strong>{{ $pending_ads }}</strong></h5>
                        </div>

                        <span class="ml-auto mr-1 float-right">
                            <i class="fa fa-chevron-right text-muted"></i>
                        </span>
                    </a>
                </div>
            </div>

            @if(!$user->profileComplete())
            <p class="info danger">
                Some information about you is not complete. Complete your profile to speed up your verification so you can use our services
                <br><a href="{{ route('web.user.account') }}" class="btn btn-link px-0 py-1"><i class="fa fa-user mr-1"></i>Go to Account</a>
            </p>
            @endif

            <div class="mb-3">
                @if(isset($verification['email'], $verification['official_phone'], $verification['business']))
                <p class="info success">
                    Your account and business are fully verified. You can now submit new adverts and manage your existing ones
                    <br><a href="{{ route('web.adverts.create') }}" class="btn btn-link px-0 py-1"><i class="fa fa-plus mr-1"></i>Create a new Advert</a>
                </p>
                @else
                <p class="info danger">
                    Your account and business are not fully verified. Until then, you will not be able to submit and manage adverts
                    Visit your account to see status and verify<br><a href="{{ route('web.user.account') }}" class="btn btn-link px-0 py-1"><i class="fa fa-user mr-1"></i>Go to Account</a>
                </p>
                @endif
            </div>

            <div class="mb-3">
                <h4 class="font-weight-600">Recent Ads</h4>

                @if(count($ads) == 0)
                <div>
                    <p class="lead info p-3 rounded border-left-0 mt-0">
                        You have not submitted any ad recently. Click on 'Upload Ad' to begin advertising digitally with us
                    </p>

                    <a href="" class="btn btn-link px-0 py-2"><i class="fa fa-plus mr-1"></i>Upload an Ad</a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table- border">
                        <tr class="bg-primary text-white">
                            <th></th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Booked Slots</th>
                            <th></th>
                        </tr>

                        @foreach ($ads as $ad)
                        <tr>
                            <td style="vertical-align: middle">
                                <span class="rounded-circle text-success d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #eee">
                                    <i class="fa fa-bullhorn" style="font-size: 1.2em"></i>
                                </span>
                            </td>
                            <td style="vertical-align: middle"><div style="max-height: 3rem; line-height: 1.5rem; overflow-y: hidden">{{ $ad->description }}</div></td>
                            <td style="vertical-align: middle">{{ $ad->category_name }}</td>
                            <td style="vertical-align: middle; text-align: center">{{ $ad->slots_count }}</td>
                            <td style="vertical-align: middle"><a href="{{ route('web.adverts.single', $ad->id) }}">View Ad</a></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @endif
            </div>

            <div>
                <h4 class="font-weight-600">Account Activity</h4>

                @php
                    $repo = resolve(\App\Repository\NotificationsRepository::class);
                    $notifications = $repo->recent(5);
                @endphp

                @if(count($notifications) == 0)
                <p class="lead mt-0">
                    We will let you know when something happens regarding your account or your adverts
                </p>
                @endif

                @foreach($notifications as $notification)
                <div class="py-3 d-flex align-items-center">
                    <span class="icon icon-shape bg-primary text-white mr-4">
                        <i class="fa fa-bell"></i>
                    </span>

                    <div>
                        <h6 class="mb-2"><strong>{{ $notification->title.' - '.$notification->time }}</strong></h6>
                        <p class="my-0">{{ $notification->content }}</p>
                    </div>
                </div>

                <hr class="my-0">
                @endforeach
            </div>

        </div>

        <div class="col-lg-3">

            <h5><strong>Quick Actions</strong></h5>

            <div class="mb-3">
                <div class="mb-1"><a href="{{ route('web.adverts.create') }}">Submit your ad</a></div>
                <div class="mb-1"><a href="{{ route('web.adverts.approved') }}">View your ads</a></div>
                <div class="mb-1"><a href="{{ route('web.user.account') }}">Manage Account</a></div>
                <div class="mb-1"><a href="{{ route('logout') }}">Sign Out</a></div>
            </div>

        </div>


    </div>

    <style>
        .col-x6{
            width: 100%;
            padding: 0 .75rem;
        }

        @media(min-width: 400px){
            .col-x6{
                width: 50%;
            }
        }
    </style>

@endsection

@section('scripts')
@if(session()->has('status'))
<script>
    showAlert(session()->get('status'));
</script>
@endif
@endsection
