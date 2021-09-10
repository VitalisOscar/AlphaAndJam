<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Agents | Add a new agent')

@section('page_heading')
<i class="fa fa-handshake-o text-success mr-3" style="font-size: .8em"></i>
Add Agent Account
@endsection

@section('content')

<style>
    .form-control{
        background: #eee;
        border-color: #eee;
    }
</style>

<div class="row">
    <div class="col-md-9 col-lg-8">

        <div class="card shadow-sm">
            <div class="card-header py-2 bg-white d-flex align-items-center">
                <h3 class="card-title mb-0 font-weight-600">Agent Info</h3>

                <a href="{{ route('admin.agents') }}" class="btn btn-sm btn-primary shadow-none ml-auto">Back to Agents</a>
            </div>

            <form method="post" action="{{ route('admin.agents.add') }}">
                @csrf

                <div class="card-body">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label class="mb-0"><strong>Agent Name:</strong></label>
                            </div>

                            <div class="col-md-9">
                                <input type="text" name="name" class="form-control" placeholder="e.g John Doe" value="{{ old('name') }}" required>
                                @if($errors->has('name'))
                                <small class="text-danger">
                                    {{ $errors->get('name')[0] }}
                                </small>
                                @else
                                <small>Enter the name of the agent to own the new account</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label class="mb-0"><strong>Phone Number:</strong></label>
                            </div>

                            <div class="col-md-9">
                                <input type="tel" minlength="10" maxlength="10" name="phone" class="form-control" placeholder="e.g 0700123456" value="{{ old('phone') }}" required>
                                @if($errors->has('phone'))
                                <small class="text-danger">
                                    {{ $errors->get('phone')[0] }}
                                </small>
                                @else
                                <small>Enter the agent's phone number (Use the format 0700123456)</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label class="mb-0"><strong>Email:</strong></label>
                            </div>

                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control" placeholder="e.g john@example.com" value="{{ old('email') }}" required>
                                @if($errors->has('email'))
                                <small class="text-danger">
                                    {{ $errors->get('email')[0] }}
                                </small>
                                @else
                                <small>The email will be used for communications and logins</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label class="mb-0"><strong>Password:</strong></label>
                            </div>

                            <div class="col-md-9">
                                <input type="text" name="password" class="form-control" placeholder="" value="{{ old('password') }}" required>
                                @if($errors->has('password'))
                                <small class="text-danger">
                                    {{ $errors->get('password')[0] }}
                                </small>
                                @else
                                <small>The agent can change this password later</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success shadow-none">Create Account</button>
                    </div>

                </div>
            </form>

        </div>

    </div>

    <div class="col-md-3 col-lg-4">
        <p class="lead">
            An agent will be able to create adverts on behalf of clients.
            You can deactivate or reactivate an agent's account any time
        </p>
    </div>
</div>

@endsection
