@extends('adminlte::page')

@section('title', 'Document Finder - Dashboard')

@section('css')
@php
    $user_role = Auth::user()->role ? Auth::user()->role->name : 'user';

    // Initialize variables with default values to prevent undefined variable errors
    $percentDocuments = 0;
    $percentPending = 0;
    $percentDownloads = 0;

    // Initialize count variables with default values
    $totalDocuments = 0;
    $controlledDocuments = 0;
    $pendingApprovals = 0;
    $totalDownloads = 0;

    // Initialize route variables with default values
    $totalDocumentsRoute = '#';
    $controlledDocumentsRoute = '#';
    $pendingApprovalsRoute = '#';
    $totalDownloadsRoute = '#';

    // Titles
    $totalDocumentsTitle = 'Total Documents';
    $controlledDocumentsTitle = 'Controlled Documents';
    $pendingApprovalsTitle = 'Pending Approvals';
    $recentDocumentsTitle = 'Recent Documents';

    // Subtitles
    $totalDocumentsSubtitle = 'No data available';
    $controlledDocumentsSubtitle = 'No data available';
    $pendingApprovalsSubtitle = 'No data available';
    $recentDocumentsSubtitle = 'No data available';

    if ($user_role == 'admin') {
        $totalDocumentsTitle = 'Total Uploads';
        $controlledDocumentsTitle = 'Uploads This Month';
        $pendingApprovalsTitle = 'Pending Review by Campus DCC';
        $recentDocumentsTitle = 'My Recent Uploads';

        $totalDocuments = $totalUploads; // Use admin specific count
        $controlledDocuments = $uploadsThisMonthCount; // Use admin specific count
        $pendingApprovals = $pendingApprovals; // Use admin specific count

        $totalDocumentsSubtitle = '+' . $percentDocuments . '% from last month';
        $controlledDocumentsSubtitle = 'Documents marked as controlled';
        $pendingApprovalsSubtitle = '+' . $percentPending . '% from last month';
        $recentDocumentsSubtitle = 'Latest document activities and updates';

    } elseif ($user_role == 'superadmin') {
        $totalDocumentsTitle = 'Total Documents';
        $controlledDocumentsTitle = 'Controlled Documents';
        $pendingApprovalsTitle = 'Pending Approvals';
        $recentDocumentsTitle = 'Recent Documents';

        $totalDocumentsSubtitle = '+' . $percentDocuments . '% from last month';
        $controlledDocumentsSubtitle = 'Documents marked as controlled';
        $pendingApprovalsSubtitle = '+' . $percentPending . '% from last month';
        $recentDocumentsSubtitle = 'Latest document activities and updates';
    } elseif ($user_role == 'campus-dcc') {
        $totalDocumentsTitle = 'Total Documents Distributed';
        $controlledDocumentsTitle = 'Pending Distributions';
        $pendingApprovalsTitle = 'Distributed This Month';
        $recentDocumentsTitle = 'Recently Distributed Documents';

        $totalDocumentsSubtitle = '+' . $percentDocuments . '% from last month';
        $controlledDocumentsSubtitle = 'Documents marked as controlled';
        $pendingApprovalsSubtitle = '+' . $percentPending . '% from last month';
        $recentDocumentsSubtitle = 'Latest document activities and updates';

    } elseif ($user_role == 'process-owner') {
        $totalDocumentsTitle = 'Documents Assigned to Me';
        $controlledDocumentsTitle = 'Pending Feedback Requests';
        $pendingApprovalsTitle = 'Feedback/Comments History';
        $recentDocumentsTitle = 'Recently Viewed Documents';

        $totalDocumentsSubtitle = 'Documents assigned by Campus DCC';
        $controlledDocumentsSubtitle = 'Documents waiting for your feedback';
        $pendingApprovalsSubtitle = 'Your submitted feedback history';
        $recentDocumentsSubtitle = 'Documents you viewed recently';
    } else {
        // Default subtitles for other roles or if no role is matched
        $totalDocumentsSubtitle = '+' . $percentDocuments . '% from last month';
        $controlledDocumentsSubtitle = 'Documents marked as controlled';
        $pendingApprovalsSubtitle = '+' . $percentPending . '% from last month';
        $recentDocumentsSubtitle = 'Latest document activities and updates';
    }
@endphp
<style>
    /* Watermark */
    #logoWatermark {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 250px;
        height: 250px;
        margin-left: -125px;
        margin-top: -125px;
        opacity: 0.05;
        pointer-events: none;
        z-index: 9999;
        animation: spin 10s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Dashboard UI Styles */
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .stats-card { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; position: relative; }
    .stats-card .icon { position: absolute; right: 20px; top: 20px; font-size: 20px; }
    .stats-card .title { font-size: 14px; color: #666; margin-bottom: 5px; }
    .stats-card .number { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
    .stats-card .subtitle { font-size: 12px; color: #999; }
    .stats-card.controlled .icon { color: #28a745; }
    .stats-card.pending .icon { color: #ffc107; }
    .stats-card.downloads .icon { color: #6c757d; }
    .quick-actions { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
    .quick-actions h3 { font-size: 18px; margin-bottom: 5px; }
    .quick-actions p { color: #666; font-size: 14px; margin-bottom: 20px; }
    .quick-actions .btn { margin-right: 10px; margin-bottom: 10px; }
    .documents-table { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; }
    .documents-table h3 { font-size: 18px; margin-bottom: 5px; }
    .documents-table p { color: #666; font-size: 14px; margin-bottom: 20px; }
    .status-badge { padding: 6px 12px; border-radius: 20px; color: white; font-size: 12px; display: inline-flex; align-items: center; font-weight: 500; min-width: 100px; justify-content: center; }
    .status-badge.controlled { background-color: #28a745; }
    .status-badge.pending { background-color: #ffc107; color: #212529; }
    .status-badge.rejected { background-color: #dc3545; }
    .status-badge.expired { background-color: #dc3545; }
    .status-badge i { margin-right: 5px; }
    .search-box { max-width: 7000px; }
</style>
@stop

@section('content_header')
<div class="dashboard-header">
    <div>
        <h1>Dashboard</h1>
        <p><strong>Welcome back, {{ Auth::user()->first_name }}</strong>
            @if(Auth::user()->administrative_position)
                <br><em style="font-size: 0.9em;">{{ Auth::user()->administrative_position }}</em>
            @endif
        </p>
    </div>
    <div class="search-box">
        <div class="input-group" style="width: 400px;">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control" id="live-search-input" placeholder="Search documents, users, or control numbers...">
        </div>
    </div>
</div>
@stop

@section('content')
<!-- Centered Spinning Watermark -->
<img src="{{ asset('images/psu_logo.png') }}" id="logoWatermark" alt="PSU Logo">

<div class="row">
    @if ($user_role == 'super-admin')
        {{-- Total Documents --}}
        <div class="col-md-3">
            <a href="{{ $totalDocumentsRoute }}" class="text-decoration-none">
                <div class="stats-card stats-card-dark">
                    <i class="fas fa-file-alt icon"></i>
                    <div class="title">{{ $totalDocumentsTitle }}</div>
                    <div class="number">{{ $totalDocuments }}</div>
                    <div class="subtitle">{{ $totalDocumentsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Controlled Documents --}}
        <div class="col-md-3">
            <a href="{{ $controlledDocumentsRoute }}" class="text-decoration-none">
                <div class="stats-card controlled">
                    <i class="fas fa-check-circle icon"></i>
                    <div class="title">{{ $controlledDocumentsTitle }}</div>
                    <div class="number">{{ $controlledDocuments }}</div>
                    <div class="subtitle">{{ $controlledDocumentsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Pending Approvals --}}
        <div class="col-md-3">
            <a href="{{ $pendingApprovalsRoute }}" class="text-decoration-none">
                <div class="stats-card pending">
                    <i class="fas fa-hourglass-half icon"></i>
                    <div class="title">{{ $pendingApprovalsTitle }}</div>
                    <div class="number">{{ $pendingApprovals }}</div>
                    <div class="subtitle">{{ $pendingApprovalsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Total Downloads --}}
        <div class="col-md-3">
            <a href="{{ $totalDownloadsRoute }}" class="text-decoration-none">
                <div class="stats-card downloads">
                    <i class="fas fa-download icon"></i>
                    <div class="title">{{ $totalDownloadsTitle }}</div>
                    <div class="number">{{ $totalDownloads }}</div>
                    <div class="subtitle">{{ $totalDownloadsSubtitle }}</div>
                </div>
            </a>
        </div>
    @elseif ($user_role == 'admin')
        {{-- Total Uploads --}}
        <div class="col-md-3">
            <a href="{{ $totalUploadsRoute }}" class="text-decoration-none">
                <div class="stats-card stats-card-dark">
                    <i class="fas fa-upload icon"></i>
                    <div class="title">{{ $totalUploadsTitle }}</div>
                    <div class="number">{{ $totalUploads }}</div>
                    <div class="subtitle">{{ $totalUploadsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Uploads This Month --}}
        <div class="col-md-3">
            <a href="{{ $uploadsThisMonthRoute }}" class="text-decoration-none">
                <div class="stats-card controlled">
                    <i class="fas fa-calendar-alt icon"></i>
                    <div class="title">{{ $uploadsThisMonthTitle }}</div>
                    <div class="number">{{ $uploadsThisMonthCount }}</div>
                    <div class="subtitle">{{ $uploadsThisMonthSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Pending Review by Campus DCC --}}
        <div class="col-md-3">
            <a href="{{ $pendingReviewByCampusDCCRoute }}" class="text-decoration-none">
                <div class="stats-card pending">
                    <i class="fas fa-user-clock icon"></i>
                    <div class="title">{{ $pendingReviewByCampusDCCTitle }}</div>
                    <div class="number">{{ $pendingReviewByCampusDCC }}</div>
                    <div class="subtitle">{{ $pendingReviewByCampusDCCSubtitle }}</div>
                </div>
            </a>
        </div>
    @elseif ($user_role == 'campus-dcc')
        {{-- Total Documents Distributed --}}
        <div class="col-md-3">
            <a href="{{ $totalDocumentsDistributedRoute }}" class="text-decoration-none">
                <div class="stats-card stats-card-dark">
                    <i class="fas fa-share-alt icon"></i>
                    <div class="title">{{ $totalDocumentsDistributedTitle }}</div>
                    <div class="number">{{ $totalDocumentsDistributed }}</div>
                    <div class="subtitle">{{ $totalDocumentsDistributedSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Pending Distributions --}}
        <div class="col-md-3">
            <a href="{{ $pendingDistributionsRoute }}" class="text-decoration-none">
                <div class="stats-card pending">
                    <i class="fas fa-hourglass-start icon"></i>
                    <div class="title">{{ $pendingDistributionsTitle }}</div>
                    <div class="number">{{ $pendingDistributions }}</div>
                    <div class="subtitle">{{ $pendingDistributionsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Distributed This Month --}}
        <div class="col-md-3">
            <a href="{{ $distributedThisMonthRoute }}" class="text-decoration-none">
                <div class="stats-card controlled">
                    <i class="fas fa-calendar-check icon"></i>
                    <div class="title">{{ $distributedThisMonthTitle }}</div>
                    <div class="number">{{ $distributedThisMonthCount }}</div>
                    <div class="subtitle">{{ $distributedThisMonthSubtitle }}</div>
                </div>
            </a>
        </div>
    @elseif ($user_role == 'process-owner')
        {{-- Documents Assigned to Me --}}
        <div class="col-md-3">
            <a href="{{ $documentsAssignedToMeRoute }}" class="text-decoration-none">
                <div class="stats-card stats-card-dark">
                    <i class="fas fa-user-tag icon"></i>
                    <div class="title">{{ $documentsAssignedToMeTitle }}</div>
                    <div class="number">{{ $documentsAssignedToMe }}</div>
                    <div class="subtitle">{{ $documentsAssignedToMeSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Pending Feedback Requests --}}
        <div class="col-md-3">
            <a href="{{ $pendingFeedbackRequestsRoute }}" class="text-decoration-none">
                <div class="stats-card pending">
                    <i class="fas fa-comment-dots icon"></i>
                    <div class="title">{{ $pendingFeedbackRequestsTitle }}</div>
                    <div class="number">{{ $pendingFeedbackRequests }}</div>
                    <div class="subtitle">{{ $pendingFeedbackRequestsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Feedback/Comment History --}}
        <div class="col-md-3">
            <a href="{{ $feedbackCommentHistoryRoute }}" class="text-decoration-none">
                <div class="stats-card controlled">
                    <i class="fas fa-history icon"></i>
                    <div class="title">{{ $feedbackCommentHistoryTitle }}</div>
                    <div class="number">{{ $feedbackCommentHistory }}</div>
                    <div class="subtitle">{{ $feedbackCommentHistorySubtitle }}</div>
                </div>
            </a>
        </div>
    @else
        {{-- Default for other roles or no specific role --}}
        {{-- Total Documents --}}
        <div class="col-md-3">
            <a href="{{ $totalDocumentsRoute }}" class="text-decoration-none">
                <div class="stats-card stats-card-dark">
                    <i class="fas fa-file-alt icon"></i>
                    <div class="title">{{ $totalDocumentsTitle }}</div>
                    <div class="number">{{ $totalDocuments }}</div>
                    <div class="subtitle">{{ $totalDocumentsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Controlled Documents --}}
        <div class="col-md-3">
            <a href="{{ $controlledDocumentsRoute }}" class="text-decoration-none">
                <div class="stats-card controlled">
                    <i class="fas fa-check-circle icon"></i>
                    <div class="title">{{ $controlledDocumentsTitle }}</div>
                    <div class="number">{{ $controlledDocuments }}</div>
                    <div class="subtitle">{{ $controlledDocumentsSubtitle }}</div>
                </div>
            </a>
        </div>
    
        {{-- Pending Approvals --}}
        <div class="col-md-3">
            <a href="{{ $pendingApprovalsRoute }}" class="text-decoration-none">
                <div class="stats-card pending">
                    <i class="fas fa-hourglass-half icon"></i>
                    <div class="title">{{ $pendingApprovalsTitle }}</div>
                    <div class="number">{{ $pendingApprovals }}</div>
                    <div class="subtitle">{{ $pendingApprovalsSubtitle }}</div>
                </div>
            </a>
        </div>
    @endif
</div>

<!-- Recent Documents Table -->
<div class="row">
    <div class="col-md-12">
        <div class="documents-table">
            <h3>{{ $recentDocumentsTitle }}</h3>
            <p>{{ $recentDocumentsSubtitle }}</p>
            <div class="table-responsive">
    <table class="table table-striped" id="documents-table">
        <thead>
            <tr>
                <th style="width: 15%">Document Title</th>
                <th style="width: 15%">Control Number</th>

                @if($user_role == 'admin')
                    <th style="width: 12%">Status</th>
                    <th style="width: 10%">Version</th>
                    <th style="width: 15%">Uploads This Month</th>
                    <th style="width: 10%">Last Modified</th>
                @elseif($user_role == 'campus-dcc')
                    <th style="width: 12%">Status</th>
                    <th style="width: 15%">Distributed To</th>
                    <th style="width: 15%">Distributed Date</th>
                    <th style="width: 10%">Last Modified</th>
                @elseif($user_role == 'process-owner')
                    <th style="width: 15%">Assigned By</th>
                    <th style="width: 12%">Status</th>
                    <th style="width: 15%">Last Viewed</th>
                    <th style="width: 12%">Feedback History</th>
                @else {{-- Super Admin --}}
                    <th style="width: 8%">Type</th>
                    <th style="width: 8%">Status</th>
                    <th style="width: 8%">Version</th>
                    <th style="width: 10%">Revisions</th>
                    <th style="width: 8%">Owner</th>
                    <th style="width: 12%">Last Modified</th>
                    <th style="width: 10%">Downloads</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($recentDocuments as $doc)
                <tr>
                    <td>{{ $doc->title }}</td>
                    <td>{{ $doc->control_number }}</td>

                    {{-- Admin --}}
                    @if($user_role == 'admin')
                        <td>
                            <span class="status-badge
                                @if($doc->status_upload == 0) pending
                                @elseif(in_array($doc->status_upload, [1,4])) controlled
                                @elseif($doc->status_upload == 2) rejected
                                @endif
                                @if($doc->is_archived) archived @endif">
                                @if($doc->status_upload == 0) Pending
                                @elseif(in_array($doc->status_upload, [1,4])) Controlled
                                @elseif($doc->status_upload == 2) Rejected
                                @endif
                                @if($doc->is_archived) Archived @endif
                            </span>
                        </td>
                        <td>{{ $doc->version }}</td>
                        <td>{{ $doc->uploads_this_month_count ?? 0 }}</td>
                        <td>{{ $doc->updated_at->format('Y-m-d H:i:s') }}</td>

                    {{-- Campus DCC --}}
                    @elseif($user_role == 'campus-dcc')
                        <td>
                            <span class="status-badge
                                @if($doc->status_upload == 0) pending
                                @elseif($doc->status_upload == 4 && $doc->status_distribution == 0) pending
                                @elseif($doc->status_distribution == 1) controlled
                                @endif">
                                @if($doc->status_upload == 0) Pending Upload
                                @elseif($doc->status_upload == 4 && $doc->status_distribution == 0) Pending Distribution
                                @elseif($doc->status_distribution == 1) Distributed
                                @endif
                            </span>
                        </td>
                        <td>{{ $doc->distributed_to_designation ?? 'N/A' }}</td>
                        <td>{{ $doc->distributed_at ? $doc->distributed_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>{{ $doc->updated_at->format('Y-m-d H:i:s') }}</td>

                    {{-- Process Owner --}}
                    @elseif($user_role == 'process-owner')
                        <td>{{ $doc->distributedBy->name ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge
                                @if($doc->status_feedback == 0) pending
                                @elseif($doc->status_feedback == 1) controlled
                                @endif">
                                @if($doc->status_feedback == 0) Pending
                                @elseif($doc->status_feedback == 1) Reviewed
                                @endif
                            </span>
                        </td>
                        <td>{{ $doc->last_viewed_at ? $doc->last_viewed_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>Feedback History</td>

                    {{-- Super Admin --}}
                    @else
                        <td>{{ $doc->type }}</td>
                        <td>{{ $doc->status_upload }}</td>
                        <td>{{ $doc->version }}</td>
                        <td>{{ $doc->revisions }}</td>
                        <td>{{ $doc->owner }}</td>
                        <td>{{ $doc->updated_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $doc->total_downloads }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .stats-card {
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        color: #fff;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .stats-card.stats-card-dark {
        background: linear-gradient(45deg, #343a40, #6c757d);
    }

    .stats-card.controlled {
        background: linear-gradient(45deg, #28a745, #218838);
    }

    .stats-card.pending {
        background: linear-gradient(45deg, #ffc107, #e0a800);
        color: #343a40;
    }

    .stats-card.downloads {
        background: linear-gradient(45deg, #17a2b8, #138496);
    }

    .stats-card .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 3em;
        color: rgba(255, 255, 255, 0.2);
    }

    .stats-card .title {
        font-size: 1.2em;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stats-card .number {
        font-size: 2.5em;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stats-card .subtitle {
        font-size: 0.9em;
        opacity: 0.8;
    }

    .products-list .product-img i {
        font-size: 2em;
        color: #6c757d;
    }

    .products-list .item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .products-list .item:last-child {
        border-bottom: none;
    }

    .products-list .product-img {
        margin-right: 10px;
    }

    .products-list .product-info {
        flex-grow: 1;
    }

    .products-list .product-title {
        font-weight: 600;
    }

    .products-list .product-description {
        color: #6c757d;
        font-size: 0.9em;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Live search functionality
        $('#live-search-input').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('.products-list .item').each(function() {
                var productTitle = $(this).find('.product-title').text().toLowerCase();
                var productDescription = $(this).find('.product-description').text().toLowerCase();
                if (productTitle.includes(searchTerm) || productDescription.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Tab switching logic
        $('.nav-link').on('click', function() {
            var targetTab = $(this).attr('href');
            $('.tab-pane').removeClass('active show');
            $(targetTab).addClass('active show');
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>
@stop

