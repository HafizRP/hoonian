@extends('layouts.admin')

@section('title', 'System Health Check')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">System Health Status</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('backoffice.index') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">System</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Health Check</a>
                </li>
            </ul>
        </div>

        <div class="row">
            {{-- Web Service --}}
            <div class="col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center {{ $webStatus == 'healthy' ? 'icon-success' : 'icon-danger' }} bubble-shadow-small">
                                    <i class="fas fa-globe"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Web Service</p>
                                    <h4 class="card-title">{{ ucfirst($webStatus) }}</h4>
                                    <small class="text-muted">{{ $webMessage }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Database --}}
            <div class="col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center {{ $dbStatus == 'healthy' ? 'icon-success' : 'icon-danger' }} bubble-shadow-small">
                                    <i class="fas fa-database"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Database</p>
                                    <h4 class="card-title">{{ ucfirst($dbStatus) }}</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <small class="text-muted">{{ $dbLatency }} ms</small>
                                        </div>
                                        @if($dbStatus != 'healthy')
                                            <div class="col-12 text-danger text-wrap" style="font-size: 10px;">
                                                {{ Str::limit($dbMessage, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pusher --}}
            <div class="col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center {{ $pusherStatus == 'healthy' ? 'icon-success' : ($pusherStatus == 'warning' ? 'icon-warning' : 'icon-danger') }} bubble-shadow-small">
                                    <i class="fas fa-broadcast-tower"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Pusher Service</p>
                                    <h4 class="card-title">{{ ucfirst($pusherStatus) }}</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <small
                                                class="text-muted">{{ $pusherLatency > 0 ? $pusherLatency . ' ms' : '' }}</small>
                                        </div>
                                        @if($pusherStatus != 'healthy')
                                            <div class="col-12 {{ $pusherStatus == 'warning' ? 'text-warning' : 'text-danger' }} text-wrap"
                                                style="font-size: 10px;">
                                                {{ Str::limit($pusherMessage, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <p class="text-muted">Last Checked: {{ $lastChecked->format('Y-m-d H:i:s') }}</p>
                <a href="{{ route('backoffice.health') }}" class="btn btn-primary btn-round">
                    <i class="fas fa-sync-alt me-2"></i> Refresh Status
                </a>
            </div>
        </div>
    </div>
@endsection