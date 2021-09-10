<?php $user = auth()->guard('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Admin Home | '.$user->name)

@section('page_heading')
<i class="fa fa-line-chart text-success mr-3" style="font-size: .8em"></i>
Dashboard
@endsection

@section('content')

<div class="row mb-5">

    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm rounded-lg" style="border-top: 3px solid orangered">
            <div class="card-body p-3">
                <div class="row mx--3 border-bottom">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted small mb-0">All Adverts</h5>
                        <span class="h2 font-weight-bold mb-0">{{ \App\Models\Advert::count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow-sm">
                        <i class="fa fa-bullhorn"></i>
                        </div>
                    </div>
                </div>

                <p class="mt-3 mb-0 text-sm">
                    <a href="{{ route('admin.adverts') }}">View Ads</a>
                    <i class="fa fa-angle-double-right"></i>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm rounded-lg" style="border-top: 3px solid rgb(10, 151, 45)">
            <div class="card-body p-3">
                <div class="row mx--3 border-bottom">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted small mb-0">Scheduled Ads</h5>
                        <span class="h2 font-weight-bold mb-0">{{ \App\Models\Advert::whereHas('any_scheduled_slot')->count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white rounded-circle shadow-sm">
                        <i class="fa fa-history"></i>
                        </div>
                    </div>
                </div>

                <p class="mt-3 mb-0 text-sm">
                    <a href="{{ route('admin.schedule', ['date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}">View Schedule</a>
                    <i class="fa fa-angle-double-right"></i>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm rounded-lg" style="border-top: 3px solid rgb(123, 10, 151)">
            <div class="card-body p-3">
                <div class="row mx--3 border-bottom">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted small mb-0">Clients</h5>
                        <span class="h2 font-weight-bold mb-0">{{ \App\Models\User::count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow-sm">
                        <i class="fa fa-user-circle"></i>
                        </div>
                    </div>
                </div>

                <p class="mt-3 mb-0 text-sm">
                    <a href="{{ route('admin.clients') }}">View Clients</a>
                    <i class="fa fa-angle-double-right"></i>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm rounded-lg" style="border-top: 3px solid rgb(209, 140, 12)">
            <div class="card-body p-3">
                <div class="row mx--3 border-bottom">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted small mb-0">Categories</h5>
                        <span class="h2 font-weight-bold mb-0">{{ \App\Models\Category::count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow-sm">
                        <i class="ni ni-bullet-list-67"></i>
                        </div>
                    </div>
                </div>

                <p class="mt-3 mb-0 text-sm">
                    <a href="{{ route('admin.categories') }}">View Categories</a>
                    <i class="fa fa-angle-double-right"></i>
                </p>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-3">
        <h4><strong>New Clients</strong></h4>

        @php
            $clients = \App\Models\User::where('verification->business', null)->get();
        @endphp

        @if(count($clients) == 0)
        <p class="lead mt-0">
            No clients are currently pending approval
        </p>

        <div>
            <a href="{{ route('admin.clients') }}" class="btn btn-primary py-2 shadow-none">View Existing Clients</a>
        </div>

        @else

        <div class="border-top">
            @foreach($clients as $client)
            <a href="{{ route('admin.clients.single', $client->email) }}" class="d-flex align-items-center p-3 border border-top-0 bg-white">
                <span class="icon icon-shape bg-gradient-default text-white mr-3" style="min-width: 45px !important">
                    <i class="fa fa-user"></i>
                </span>

                <div style="max-width: calc(100% - 1rem - 40px) !important">
                    <h5 class="mb-0 text-truncate"><span>{{ $client->name }}</span></h5>
                    <p class="mb-0 text-body">
                        {{ 'Since '.$client->time }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>

        @endif
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header bg-default py-3 border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0 text-white">Recent Ad Uploads</h3>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('admin.adverts') }}" class="btn btn-sm btn-white shadow-none">See all</a>
                    </div>
                </div>
            </div>

          <div class="table-responsive">
            <table class="table align-items-center mb-0">

                <tr class="bg-lighter">
                    <th scope="col">Category</th>
                    <th scope="col">About</th>
                    <th scope="col">Client</th>
                    <th scope="col">Status</th>
                    <th></th>
                </tr>

                @php
                    $repo = resolve(\App\Repository\AdminAdvertRepository::class);
                    $recent = $repo->getRecent();
                @endphp

                @foreach($recent as $advert)
                <tr>
                    <td style="vertical-align: middle">{{ $advert->category_name }}</td>
                    <td style="width: 300px; vertical-align: middle">{{ $advert->description }}</td>
                    <td style="vertical-align: middle">{{ $advert->user->name }}</td>
                    <td style="vertical-align: middle">
                        @if($advert->isApproved())
                        <span class="text-success">Approved</span>
                        @elseif($advert->isRejected())
                        <span class="text-danger">Rejected</span>
                        @else
                        <span class="text-primary">Pending</span>
                        @endif
                    </td>
                    <td style="vertical-align: middle">
                        {{ $advert->time }}<br>
                        <a href="{{ route('admin.adverts.single', $advert->id) }}">View Ad</a>
                    </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

</div>

@endsection
