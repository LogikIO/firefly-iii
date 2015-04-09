@extends('layouts.default')
@section('content')
{!! Breadcrumbs::renderIfExists() !!}
@if($count == 0)
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <p class="lead">Welcome to Firefly III.</p>

        <p>
            Create a new asset account to get started.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h2><a href="{{route('accounts.create','asset')}}">Start from scratch</a></h2>
    </div>
    @else

<!-- fancy new boxes -->
    @include('partials.boxes')




<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12">
        <!-- ACCOUNTS -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-credit-card fa-fw"></i> <a href="#">Your accounts</a>
            </div>
            <div class="panel-body">
                <div id="accounts-chart"></div>
            </div>
        </div>
        <!-- BUDGETS -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-tasks fa-fw"></i> <a href="{{route('budgets.index')}}">Budgets and spending</a>
            </div>
            <div class="panel-body">
                <div id="budgets-chart"></div>
            </div>
        </div>
        <!-- CATEGORIES -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart fa-fw"></i> <a href="{{route('categories.index')}}">Categories</a>
            </div>
            <div class="panel-body">
                <div id="categories-chart"></div>
            </div>
        </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-line-chart"></i> Savings
                        <span class="pull-right">{!! Amount::format($savingsTotal) !!}</span>
                    </div>
                    <div class="panel-body">
                        @if(count($savings) == 0)
                            <p class="small"><em>Mark your asset accounts as "Savings account" to fill this panel.</em></p>
                        @else
                            @foreach($savings as $account)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h5><a href="{{route('accounts.show')}}">{{$account->name}}</a></h5></div>
                                </div>
                                <div class="row">
                                    <!-- start -->
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">{!! Amount::format($account->startBalance) !!}</div>
                                    <!-- bar -->
                                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-4">
                                        @if($account->difference < 0)
                                            <!-- green (100-pct), then red (pct) -->
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" style="width: {{100 - $account->percentage}}%">
                                                    @if($account->percentage <= 50)
                                                        {{Amount::format($account->difference,false)}}
                                                    @endif
                                                </div>
                                                <div class="progress-bar progress-bar-danger progress-bar-striped" style="width: {{$account->percentage}}%">
                                                    @if($account->percentage > 50)
                                                        {{Amount::format($account->difference,false)}}
                                                    @endif
                                                </div>
                                            </div>
                                            @else
                                            <!-- green (pct), then blue (100-pct) -->
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" style="width: {{$account->percentage}}%">
                                                    @if($account->percentage > 50)
                                                        {{Amount::format($account->difference,false)}}
                                                    @endif
                                                </div>
                                                <div class="progress-bar progress-bar-info progress-bar-striped" style="width: {{100 - $account->percentage}}%">
                                                    @if($account->percentage <= 50)
                                                        {{Amount::format($account->difference,false)}}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <!-- end -->
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">{!! Amount::format($account->endBalance) !!}</div>
                                    </div>
                            @endforeach
                        @endif
                    </div>
                </div>



    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">

        <!-- REMINDERS -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-calendar-o"></i> <a href="{{route('bills.index')}}">Bills</a>
            </div>
            <div class="panel-body">
                <div id="bills-chart"></div>
            </div>
        </div>

        <!-- TRANSACTIONS -->
        @foreach($transactions as $data)
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-money fa-fw"></i>
                <a href="{{route('accounts.show',$data[1]->id)}}">{{{$data[1]->name}}}</a> ({!! Amount::format(Steam::balance($data[1])) !!})


                <!-- ACTIONS MENU -->
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            Actions
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="{{route('transactions.create','withdrawal')}}?account_id={{{$data[1]->id}}}"><i class="fa fa-long-arrow-left fa-fw"></i> New withdrawal</a></li>
                            <li><a href="{{route('transactions.create','deposit')}}?account_id={{{$data[1]->id}}}"><i class="fa fa-long-arrow-right fa-fw"></i> New deposit</a></li>
                            <li><a href="{{route('transactions.create','transfer')}}?account_from_id={{{$data[1]->id}}}"><i class="fa fa-fw fa-exchange"></i> New transfer</a></li>
                        </ul>
                    </div>
                </div>



            </div>
            <div class="panel-body">
                @include('list.journals-tiny',['transactions' => $data[0],'account' => $data[1]])
            </div>
        </div>
        @endforeach
    </div>
</div>

@endif


@stop
@section('scripts')
<script type="text/javascript">
    var currencyCode = '{{Amount::getCurrencyCode()}}';
</script>
<!-- load the libraries and scripts necessary for Google Charts: -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/gcharts.options.js"></script>
<script type="text/javascript" src="js/gcharts.js"></script>



        <script type="text/javascript" src="js/index.js"></script>
@stop
@section('styles')
@stop
