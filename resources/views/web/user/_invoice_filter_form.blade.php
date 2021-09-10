<?php $request = request(); ?>

<form method="GET" class="invoice-filters">

    <div class="p-4 border rounded bg-white">

        <div class="form-group">
            <div class="mr-2">
                <strong>Invoice Status:</strong>
            </div>
            <div class="clearfix">
                <select class="nice-select w-100" name="status">
                    <option value="">Any</option>
                    <option value="paid" @if($request->get('status') == 'paid'){{ __('selected') }}@endif >Paid</option>
                    <option value="unpaid" @if($request->get('status') == 'unpaid'){{ __('selected') }}@endif >Unpaid</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div>
                <strong>From:</strong>
            </div>
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
                <input type="date" name="date_from" value="{{ $request->filled('date_from') ? $request->get('date_from') : null }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <div>
                <strong>To:</strong>
            </div>
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
                <input type="date" name="date_to" value="{{ $request->filled('date_to') ? $request->get('date_to') : null }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <div class="mr-2">
                <strong>Order By:</strong>
            </div>
            <div class="clearfix">
                <select class="nice-select w-100" name="order">
                    <option value="">Most Recent</option>
                    <option value="oldest" @if($request->get('order') == 'oldest'){{ __('selected') }}@endif >Oldest</option>
                    <option value="highest" @if($request->get('order') == 'highest'){{ __('selected') }}@endif>Amount - Highest</option>
                    <option value="lowest" @if($request->get('order') == 'lowest'){{ __('selected') }}@endif>Amount - Lowest</option>
                </select>
            </div>
        </div>

        <div>
            <button class="btn btn-default shadow-none btn-block">
                Update
            </button>
        </div>

    </div>

</form>
